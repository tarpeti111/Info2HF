import * as THREE from "three";
import { OrbitControls } from "three/addons/controls/OrbitControls.js";

const widht = 1000;
const height = 1000;


const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera( 75, widht / height, 0.1, 1000 );

const renderer = new THREE.WebGLRenderer();
renderer.setSize( widht, height );
renderer.setAnimationLoop( animate );
document.body.appendChild( renderer.domElement );

const geometry = new THREE.BoxGeometry( 1, 1, 1 );
const material = new THREE.MeshBasicMaterial( { color: 0x00ff00 } );
const cube = new THREE.Mesh( geometry, material );
scene.add( cube );

const controls = new OrbitControls( camera, renderer.domElement );

camera.position.z = 5;

controls.update();

function animate() {

    //controls.update();

	renderer.render( scene, camera );

}