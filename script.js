
			import * as THREE from '../build/three.module.js';
            import { OrbitControls } from './jsm/controls/OrbitControls.js';								//import "OrbitControls" du fichier js
                  import { GLTFLoader } from './jsm/loaders/GLTFLoader.js';
            import { FBXLoader } from './jsm/loaders/FBXLoader.js';
      
                  let camera, camera2, scene, sceneScooter, renderer;
                  let mesh;
            let sphere;
            let upPush=0, downPush=0, leftPush=0, rightPush=0 ;
            let controlsCamera;
                  let trottinette;
                  let ambientLight;
                  let rotateDefaultX=90;
                  let rotateDefaultY=90;
                  let rotateDefaultZ=0;
                  let rotateSphere=0;
      
                  init();
                  animate();

                  function init() {
      
                    console.log('hello');
                    const canvas = document.querySelector('canvas.webgl');

                      scene = new THREE.Scene();
                      scene.background = new THREE.Color( 0x80E6FF );
                      sceneScooter = new THREE.Scene();
      
                      const texture = new THREE.TextureLoader().load( 'textures/floors/FloorsCheckerboard_S_Diffuse.jpg' );
                      const textureTerre = new THREE.TextureLoader().load( 'textures/planets/earth_atmos_4096.jpg' );
      
                      //planete
              const geometry = new THREE.SphereGeometry( 100, 64, 64 );
              const material = new THREE.MeshBasicMaterial( { map: textureTerre } );
              sphere = new THREE.Mesh( geometry, material );
              scene.add( sphere );
      
      
                    
                      //trottinette
                      const loader10 = new GLTFLoader();
                      loader10.load( './models/gltf/PA/Scooter.glb', function ( gltf ) { //GL Transmission Format
                      trottinette = gltf.scene;
                      trottinette.scale.set(9,9,9); //étirement du modèle
                      trottinette.position.set(0,0,101); //position trottinette
                      trottinette.castShadow=true;
                      trottinette.receiveShadow = true;
                      sceneScooter.add(trottinette);
      
                      trottinette.rotation.x+=THREE.Math.degToRad(rotateDefaultX);
                      trottinette.rotation.y+=THREE.Math.degToRad(rotateDefaultY);
                      trottinette.rotation.z+=THREE.Math.degToRad(rotateDefaultZ);
      
                  });
      
      
      
      
                  //Lumière ambiante
                  ambientLight = new THREE.AmbientLight( 0xffffff, 2 );  // lumière globale sans position, partout dans la scene. Sert à donner un mini éclairage aux endroits qui ne prennent aucun rayon direct de la lumière, pour imiter la vraie vie.
                  scene.add( ambientLight );
      
      
      
      
      
      
      
      
      
      
                      renderer = new THREE.WebGLRenderer( { antialias: true, canvas: canvas} );
                      renderer.setPixelRatio( window.devicePixelRatio );
                      renderer.setSize( window.innerWidth, window.innerHeight );
                      document.body.appendChild( renderer.domElement );
      
                      //
      
      
      
              //Orbit Controls
                      camera = new THREE.PerspectiveCamera( 70, window.innerWidth / window.innerHeight, 1, 1000 );
                      //CAMERA FPS
                      camera.position.set(0,-70,200) ;
                      //camera.rotation.x=THREE.Math.degToRad(55);
                      camera.rotation.x=THREE.Math.degToRad(20);
      
              //controlsCamera = new OrbitControls( camera, renderer.domElement );
      
                      sceneScooter.add(camera);
                      scene.add(sceneScooter);
      
                      window.addEventListener( 'resize', onWindowResize );
              document.addEventListener('keyup', onKeyUp);					//écouter le clavier : lors d'un relachement de touche
                      document.addEventListener('keydown', onKeyDown);			//écouter le clavier : lorsque touche enfoncée
      
                  }
      
                  function onWindowResize() {
      
                      camera.aspect = window.innerWidth / window.innerHeight;
                      camera.updateProjectionMatrix();
      
                      renderer.setSize( window.innerWidth, window.innerHeight );
      
                  }
      
            function onKeyDown() {
      
              if (event.code === 'ArrowUp' ) {
                upPush=1;
                }
              if (event.code === 'ArrowDown' ) {
                downPush=1;
      
                }
              if (event.code === 'ArrowLeft' ) {
                leftPush=1;
                }
              if (event.code === 'ArrowRight' ) {
                rightPush=1;
                }
              }//fin onKeyDown
      
            function onKeyUp() {
      
              if (event.code === 'ArrowUp' ) {
                upPush=0;
                }
              if (event.code === 'ArrowDown' ) {
                downPush=0;
                }
              if (event.code === 'ArrowLeft' ) {
                leftPush=0;
                }
              if (event.code === 'ArrowRight' ) {
                rightPush=0;
                }
              } //fin onKeyUp
      
      
      
      
      
      
      
      
      
                  function animate() {
      
                      requestAnimationFrame( animate );
      
      //PLANETE ROTATION
                      //limiter la rotation de la Terre a 360/-360°
                      rotateSphere=THREE.Math.radToDeg(sphere.rotation.y);
                      if ((rotateSphere<361 && rotateSphere>359) || (rotateSphere>-361 && rotateSphere<-359)  ) {
                          scene.rotation.y=THREE.Math.radToDeg(0);
                      }
      
                      if (upPush==1) {
                          sceneScooter.rotateX(-0.005);
              }
              if (leftPush==1 && upPush==1) {
                          sceneScooter.rotateZ(0.03);
              }
              if (rightPush==1 && upPush==1) {
                          sceneScooter.rotateZ(-0.03);
              }
      
      
                      renderer.render( scene, camera );
      
                  }