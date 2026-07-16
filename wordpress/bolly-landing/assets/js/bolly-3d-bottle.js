(function () {
  'use strict';

  var PURPLE = 0x7b56d3;
  var instances = new WeakMap();

  function createLabelTexture() {
    var canvas = document.createElement('canvas');
    canvas.width = 1024;
    canvas.height = 2048;
    var ctx = canvas.getContext('2d');

    ctx.fillStyle = '#7b56d3';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.fillStyle = '#ffffff';
    ctx.textAlign = 'center';
    ctx.font = 'bold 220px Montserrat, Arial, sans-serif';
    ctx.fillText('bolly', canvas.width / 2, 520);

    ctx.font = '500 72px Montserrat, Arial, sans-serif';
    ctx.fillText('Clarify', canvas.width / 2, 640);
    ctx.fillText('Shampoo', canvas.width / 2, 730);

    ctx.font = '400 36px Montserrat, Arial, sans-serif';
    ctx.fillStyle = 'rgba(255,255,255,0.85)';
    ctx.fillText('For all hair types', canvas.width / 2, 1680);
    ctx.fillText('250ml / 8.4 fl oz', canvas.width / 2, 1740);

    var texture = new THREE.CanvasTexture(canvas);
    texture.anisotropy = 8;
    return texture;
  }

  function buildBottle() {
    var group = new THREE.Group();

    var bodyGeometry = new THREE.CylinderGeometry(1.35, 1.35, 4.2, 64);
    var labelTexture = createLabelTexture();
    var bodyMaterial = new THREE.MeshStandardMaterial({
      map: labelTexture,
      color: 0xffffff,
      roughness: 0.55,
      metalness: 0.05,
    });
    var body = new THREE.Mesh(bodyGeometry, bodyMaterial);
    body.castShadow = true;
    body.receiveShadow = true;
    group.add(body);

    var neckGeometry = new THREE.CylinderGeometry(0.42, 0.55, 0.55, 48);
    var whiteMaterial = new THREE.MeshStandardMaterial({
      color: 0xf4f4f4,
      roughness: 0.35,
      metalness: 0.02,
    });
    var neck = new THREE.Mesh(neckGeometry, whiteMaterial);
    neck.position.y = 2.35;
    neck.castShadow = true;
    group.add(neck);

    var pumpBaseGeometry = new THREE.CylinderGeometry(0.5, 0.5, 0.35, 48);
    var pumpBase = new THREE.Mesh(pumpBaseGeometry, whiteMaterial);
    pumpBase.position.y = 2.8;
    pumpBase.castShadow = true;
    group.add(pumpBase);

    var pumpStemGeometry = new THREE.CylinderGeometry(0.12, 0.12, 0.9, 24);
    var pumpStem = new THREE.Mesh(pumpStemGeometry, whiteMaterial);
    pumpStem.position.set(0.35, 3.45, 0);
    pumpStem.rotation.z = -0.35;
    pumpStem.castShadow = true;
    group.add(pumpStem);

    var pumpHeadGeometry = new THREE.BoxGeometry(0.55, 0.22, 0.35);
    var pumpHead = new THREE.Mesh(pumpHeadGeometry, whiteMaterial);
    pumpHead.position.set(0.62, 3.92, 0);
    pumpHead.rotation.z = -0.35;
    pumpHead.castShadow = true;
    group.add(pumpHead);

    group.rotation.z = -0.28;
    group.rotation.x = 0.08;

    return group;
  }

  function initViewer(container) {
    if (instances.has(container) || typeof THREE === 'undefined') {
      return;
    }

    var width = container.clientWidth || 400;
    var height = container.clientHeight || 520;

    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera(38, width / height, 0.1, 100);
    camera.position.set(0, 0.2, 8.5);

    var renderer = new THREE.WebGLRenderer({
      antialias: true,
      alpha: true,
      powerPreference: 'high-performance',
    });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.setSize(width, height, false);
    renderer.outputEncoding = THREE.sRGBEncoding;
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    container.appendChild(renderer.domElement);

    var ambient = new THREE.AmbientLight(0xffffff, 0.75);
    scene.add(ambient);

    var keyLight = new THREE.DirectionalLight(0xffffff, 1.1);
    keyLight.position.set(4, 6, 5);
    keyLight.castShadow = true;
    scene.add(keyLight);

    var fillLight = new THREE.DirectionalLight(0xd8c8ff, 0.55);
    fillLight.position.set(-5, 2, 3);
    scene.add(fillLight);

    var rimLight = new THREE.DirectionalLight(0xffffff, 0.35);
    rimLight.position.set(0, -2, -4);
    scene.add(rimLight);

    var bottle = buildBottle();
    scene.add(bottle);

    var controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.08;
    controls.enableZoom = false;
    controls.enablePan = false;
    controls.rotateSpeed = 0.75;
    controls.minPolarAngle = Math.PI * 0.25;
    controls.maxPolarAngle = Math.PI * 0.75;
    controls.target.set(0, 0.2, 0);

    var state = {
      scene: scene,
      camera: camera,
      renderer: renderer,
      controls: controls,
      frameId: null,
      resizeObserver: null,
    };
    instances.set(container, state);

    function renderFrame() {
      state.frameId = requestAnimationFrame(renderFrame);
      controls.update();
      renderer.render(scene, camera);
    }
    renderFrame();

    function resize() {
      var nextWidth = container.clientWidth;
      var nextHeight = container.clientHeight;
      if (!nextWidth || !nextHeight) {
        return;
      }
      camera.aspect = nextWidth / nextHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(nextWidth, nextHeight, false);
    }

    if (typeof ResizeObserver !== 'undefined') {
      state.resizeObserver = new ResizeObserver(resize);
      state.resizeObserver.observe(container);
    } else {
      window.addEventListener('resize', resize);
    }

    resize();
  }

  function initAll() {
    var viewers = document.querySelectorAll('[data-bolly-3d]');
    viewers.forEach(function (viewer) {
      initViewer(viewer);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }

  if (typeof window.elementorFrontend !== 'undefined') {
    window.elementorFrontend.hooks.addAction(
      'frontend/element_ready/bolly_3d_bottle.default',
      function ($scope) {
        var viewer = $scope[0].querySelector('[data-bolly-3d]');
        if (viewer) {
          initViewer(viewer);
        }
      }
    );

    window.elementorFrontend.hooks.addAction(
      'frontend/element_ready/bolly_landing_page.default',
      function ($scope) {
        var viewer = $scope[0].querySelector('[data-bolly-3d]');
        if (viewer) {
          initViewer(viewer);
        }
      }
    );
  }
})();
