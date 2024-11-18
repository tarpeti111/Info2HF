import * as THREE from "three";
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

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

// Variables for model and stars
let model = null;
let objectOfInterest = undefined;
let newInterest = false;
const modelName = "earth";
const modelScale = 0.05;
const stars = [];
const rowsToStars = [];

// GLTF Loader
const loader = new GLTFLoader();
loader.load(`../gltf/${modelName}/scene.gltf`, (gltf) => {
    model = gltf.scene;
    model.scale.set(modelScale, modelScale, modelScale);
    model.visible = false;
    scene.add(model);
});

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
    
    var matcolor = (lum > 255/2) ? 0x000000 : 0xffffff;

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

// Function to handle row click events
function handleRowClick() {
    objectOfInterest = stars[this.rowIndex];
    objectOfInterest.scale.set(10, 10, 10);
    newInterest = true;
}

// Attach event listeners to table rows
const rows = document.getElementsByTagName('tr');
for (let i = 1; i < rows.length; i++) {
    rows[i].addEventListener('click', handleRowClick);
}

// Smoothly move the camera to the target object
function moveCameraToObject(object) {
    const targetVector = new THREE.Vector3().subVectors(object.position, camera.position);
    const targetDirection = targetVector.clone().normalize();

    // Calculate quaternion for smooth rotation
    const targetQuaternion = new THREE.Quaternion().setFromUnitVectors(
        new THREE.Vector3(0, 0, -1), // Default camera forward direction
        targetDirection
    );

    // Smooth rotation
    camera.quaternion.slerp(targetQuaternion, 0.02);

    // Smooth position movement
    if (targetVector.lengthSq() > 100) {
        const speed = targetVector.lengthSq() < 10000
            ? targetVector.length() * 0.03
            : 10.0;

        const direction = new THREE.Vector3();
        camera.getWorldDirection(direction);
        camera.position.addScaledVector(direction, speed);
    }
}

// Initialize stars
makeStars(2000);

// Animation loop
function animate() {
    if (objectOfInterest) {
        if (newInterest) {
            if (model) {
                model.position.copy(objectOfInterest.position);
                model.visible = true;
            }
            objectOfInterest.visible = false;
            newInterest = false;
        }
        moveCameraToObject(objectOfInterest);
    }

    if (model) {
        model.rotation.y += clock.getDelta() * 0.1;
    }

    resize();
    renderer.render(scene, camera);
}

// Start the animation loop
renderer.setAnimationLoop(animate);