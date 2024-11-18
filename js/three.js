import * as THREE from "three";
import { OrbitControls } from "three/addons/controls/OrbitControls.js";
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

// Initialize clock and scene
const clock = new THREE.Clock();
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, 1, 0.1, 10000);
const canvas = document.querySelector("canvas")
const renderer = new THREE.WebGLRenderer({ canvas: canvas });
document.body.appendChild(renderer.domElement);
renderer.setPixelRatio(window.devicePixelRatio);

// Set scene background color based on body background color
const body = document.body;
const backgroundColor = window.getComputedStyle(body).backgroundColor;
const [r, g, b] = backgroundColor.match(/\d+/g).map(Number);
scene.background = new THREE.Color(backgroundColor);
const lum = 0.299 * r + 0.587 * g + 0.114 * b;

// Set up ambient lights
const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
scene.add(ambientLight);

// Load appropriate model and background texture based on luminosity
var modelName = lum >= 127.5 ? "spacestation_light" : "spacestation";
let model = null;
var modelScale = 1;

const loader = new GLTFLoader();
loader.load(`../gltf/${modelName}/scene.gltf`, (gltf) => {
    model = gltf.scene;
    scene.add(model);
    model.scale.set(modelScale, modelScale, modelScale)
});

if (lum < 127.5) {
    const texture = new THREE.TextureLoader().load("../resources/images/dark_space.jpg");
    texture.colorSpace = THREE.SRGBColorSpace;
    scene.background = texture;

    camera.position.set(-10, 60, 50);
    camera.rotation.set(100, 0, 50);

    // Set up lighting
    const directionalLight = new THREE.DirectionalLight(0xFFFFFF, 0.5);
    directionalLight.position.set(20, -10, -20);
    scene.add(directionalLight);

    const directionalLightBlue = new THREE.DirectionalLight(0x00FFFF, 1);
    directionalLightBlue.position.set(-20, 10, -20);
    scene.add(directionalLightBlue);
}
else{
    const texture = new THREE.TextureLoader().load("../resources/images/light_space.jpg");
    //texture.colorSpace = THREE.SRGBColorSpace;
    
    scene.background = texture;

    camera.position.set(0, 0, 7);
    const directionalLightBlue = new THREE.DirectionalLight(0xEEEEEFF, 1);
    directionalLightBlue.position.set(15, 0, 10);
    scene.add(directionalLightBlue);
}

// Adjust canvas size and camera aspect ratio on resize
function resize() {
    const width = canvas.clientWidth;
    const height = canvas.clientHeight;
    if (width !== canvas.width || height !== canvas.height) {
        renderer.setSize(width, height, false);
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
    }
}

var fisrtFrame = true;

// Animation loop
function animate() {
    if (model) {
        if (lum < 127.5){
            model.rotation.y += clock.getDelta() * 0.2;
        }
        else{
            model.rotateX(clock.getDelta() * 0.1);
            if(fisrtFrame)
            {
                model.rotation.set(0.3,-2.4,-0.15);
                model.position.set(3,1,0)
                fisrtFrame = false;
            }
        }
    }
    
    resize();
    
    renderer.render(scene, camera);
}

renderer.setAnimationLoop(animate);