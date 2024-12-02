import * as THREE from "three";
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

let missions = [];

(function() {
    fetch('../php/get_most_common_ships_of_missions.php')
        .then(response => response.json())
        .then(responseJson => {
            missions = responseJson;
        })
        .catch(error => console.error('Error fetching options:', error));
})();

// Scene setup
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, 1, 0.1, 10000);
const canvas = document.querySelector("canvas");
const renderer = new THREE.WebGLRenderer({ canvas: canvas });
document.body.appendChild(renderer.domElement);
renderer.setPixelRatio(window.devicePixelRatio);

// Clock for animations
const clock = new THREE.Clock();

// Scene background color based on body background
const body = document.body;
const backgroundColor = window.getComputedStyle(body).backgroundColor;
const [r, g, b] = backgroundColor.match(/\d+/g).map(Number);
scene.background = new THREE.Color(backgroundColor);
const lum = 0.299 * r + 0.587 * g + 0.114 * b;

// Ambient light setup
const ambientLight = new THREE.AmbientLight(0xffffff);
scene.add(ambientLight);

const ambientLight2 = new THREE.AmbientLight(0xffffff, 1);
scene.add(ambientLight2);

// Variables for model and stars
let models = {};
let objectOfInterest = undefined;
let newInterest = false;

const modelNames = ["battleCruiser", "earth", "io"];
const modelScales= {
    'battleCruiser': 1,
    'earth': 0.15,
    'io': 10
}
const stars = [];
const rowsToStars = [];

// GLTF Loader
const loader = new GLTFLoader();
modelNames.forEach((modelName) => {
    loader.load(`../resources/models/${modelName}/scene.gltf`, (gltf) => {
        models[modelName] = gltf.scene;
        models[modelName].scale.set(modelScales[modelName], modelScales[modelName], modelScales[modelName]);
        models[modelName].visible = false;
        scene.add(gltf.scene);

        gltf.scene.traverse(function(child) {
            if (child.isMesh) {
                const material = child.material;
                material.roughness = 0.5;
                material.metalness = 0.5;
            }
        });
    })
})

console.log(models)

// Resize function to handle viewport adjustments
function resize() {
    const width = canvas.clientWidth;
    const height = canvas.clientHeight;
    if (width !== canvas.width || height !== canvas.height) {
        renderer.setSize(width, height, false);
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
    }
}

if(lum < 255/2){
    ambientLight.intensity = 0.5
}
else{
    ambientLight.intensity = 2;
}

// Function to create stars in the scene
function makeStars(maxStars) {

    for (let i = 0; i < maxStars; i++) {
        const geometry = new THREE.SphereGeometry(Math.random() * 10, 6, 6);
        const color = new THREE.Color(0xffffff);

        if (Math.random() > 0.8) {
            color.setRGB(Math.random() * 0.7, Math.random() * 0.7, (Math.random() + 0.5) / 1.5);
        } else {
            color.setRGB((Math.random() + 1) / 2, (Math.random() + 0.5) / 1.5, Math.random() * 0.5);
        }

        const material = new THREE.MeshStandardMaterial({
            color: 0x000000,
            emissive: color
        });
        const star = new THREE.Mesh(geometry, material);
        const range = 8000;
        star.position.set(
            Math.random() * range - range / 2,
            Math.random() * range - range / 2,
            Math.random() * range - range / 2
        );

        stars.push(star);
        scene.add(star);
    }

    for (let i = 0; i < rows.length; i++) {
        rowsToStars[i] = Math.round(Math.random() * maxStars);
    }
}
let modelToShow;
// Function to handle row click events
function handleRowClick() {
    objectOfInterest = stars[this.rowIndex];
    objectOfInterest.scale.set(10, 10, 10);
    newInterest = true;
    for (let i = 0; i < missions.length; i++) {
        if(missions[i].mission_title === this.textContent.trim()){
            console.log(missions[i].most_common_spaceship_type)

            //decide what model to show based on most common type of ship on mission
            switch (missions[i].most_common_spaceship_type.toLowerCase()) {
                case 'scout':
                case 'transport':
                case 'resupply':
                case 'dreadnaught':
                    modelToShow = "battleCruiser"
                    break;
                case 'exploration':
                case 'research':
                case 'mining':
                    modelToShow = "io";
                    break;
                case 'colony':
                default:
                    modelToShow = "earth"
            }
            console.log(models[modelToShow])
            break;
        }
    }
}

// Attach event listeners to table rows
const rows = document.getElementsByTagName('tr');
for (let i = 1; i < rows.length; i++) {
    rows[i].addEventListener('click', handleRowClick);
}

let isRevolving = false; // State flag for revolving behavior
let hasReachedTarget = false; // Flag to ensure snapping happens only once
const approachDistance = 30; // Distance to stop approaching
const revolveSpeed = 0.1; // Speed of revolution
const moveSpeed = 100; // Movement speed multiplier
const rotationSpeed = 0.05; // Slerp rotation speed
const stoppingThreshold = 5; // Strict threshold for stopping (tighter tolerance)
let angle = 0; // current rotation angle
const yOffset = 10; // Y-axis offset
const minMoveSpeed = 5; // minimum movement speed multiplier

function moveCameraToObject(object) {
    const objectPosition = object.position.clone();
    const cameraPosition = camera.position.clone();

    if (!isRevolving) {
        // Calculate direction and target position for approach
        const directionToObject = new THREE.Vector3().subVectors(objectPosition, cameraPosition).normalize();
        const targetPosition = objectPosition.clone().add(directionToObject.multiplyScalar(-approachDistance));
        targetPosition.y = objectPosition.y + yOffset; // Apply yOffset to target

        const targetVector = new THREE.Vector3().subVectors(targetPosition, cameraPosition);
        const distance = targetVector.length();

        // Check if camera has reached the target position
        if (!hasReachedTarget && distance > stoppingThreshold) {
            let newMoveSpeed = moveSpeed
            if(distance < 1000){
                newMoveSpeed = moveSpeed * (distance/1000);
            }
            if(newMoveSpeed < 5){
                newMoveSpeed = 5;
            }
            // Smooth movement towards the target
            const moveStep = targetVector.clone().normalize().multiplyScalar(newMoveSpeed * 0.1); // Adjust speed multiplier
            camera.position.add(moveStep);

            // Lock y position to avoid oscillation
            camera.position.y = targetPosition.y; // Ensure the camera stays at the correct height

            // Smooth rotation to look at the object using slerp
            const targetDirection = new THREE.Vector3().subVectors(objectPosition, camera.position).normalize();
            const targetQuaternion = new THREE.Quaternion().setFromUnitVectors(
                new THREE.Vector3(0, 0, -1), // Camera's default forward direction
                targetDirection
            );
            camera.quaternion.slerp(targetQuaternion, rotationSpeed); // Smooth rotation
        } else if (!hasReachedTarget) {
            // Snap to target position and stop movement
            camera.position.copy(targetPosition); // Snap to the exact position
            camera.position.y = targetPosition.y; // Ensure y-level alignment (apply yOffset)
            hasReachedTarget = true; // Lock position
            isRevolving = true; // Transition to revolving phase
            const direction = new THREE.Vector3().subVectors(camera.position, objectPosition);
            angle = Math.atan2(direction.z, direction.x); // Calculate the angle in radians
        }
    } else {
        angle += clock.getDelta() * revolveSpeed;
        // Revolve around the object
        const radius = approachDistance; // Same as the target distance
        const x = objectPosition.x + radius * Math.cos(angle);
        const z = objectPosition.z + radius * Math.sin(angle);

        camera.position.set(x, objectPosition.y + yOffset, z); // Update position on circular path
        camera.lookAt(object.position);
    }
}
// Initialize stars
makeStars(2000);

// Animation loop
function animate() {
    if (objectOfInterest) {
        //reset variables and visibilities if new ineterest is found
        if (newInterest) {
            if (models[modelToShow]) {
                Object.keys(models).forEach((model) => {
                    models[model].visible = false;
                })
                // move model to target position and set visible
                models[modelToShow].position.copy(objectOfInterest.position);
                models[modelToShow].visible = true
            }
            objectOfInterest.visible = false;
            isRevolving = false;
            hasReachedTarget = false;
            newInterest = false;
        }
        moveCameraToObject(objectOfInterest); // Handles camera behavior
        
    }
    resize(); // Adjusts canvas size if needed
    renderer.render(scene, camera); // Renders the scene
}

// Start the animation loop
renderer.setAnimationLoop(animate);