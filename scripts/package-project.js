const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const root = path.resolve(__dirname, '..');
const distDir = path.join(root, 'dist');

if (!fs.existsSync(distDir)) {
  fs.mkdirSync(distDir);
}

const pluginSource = path.join(root, 'wordpress', 'bolly-landing');
const pluginZip = path.join(distDir, 'bolly-landing.zip');
const projectZip = path.join(distDir, 'frontend-assignment.zip');

if (process.platform === 'win32') {
  if (fs.existsSync(pluginZip)) {
    fs.unlinkSync(pluginZip);
  }
  execSync(
    `powershell -NoProfile -Command "Compress-Archive -Path '${pluginSource}\\*' -DestinationPath '${pluginZip}' -Force"`,
    { stdio: 'inherit' }
  );

  if (fs.existsSync(projectZip)) {
    fs.unlinkSync(projectZip);
  }

  const items = [
    'README.md',
    'SUBMISSION.md',
    'docker-compose.yml',
    'package.json',
    'preview',
    'wordpress',
    'elementor-templates',
    'scripts',
  ];

  const tempDir = path.join(distDir, 'project-bundle');
  if (fs.existsSync(tempDir)) {
    fs.rmSync(tempDir, { recursive: true, force: true });
  }
  fs.mkdirSync(tempDir, { recursive: true });

  items.forEach((item) => {
    const source = path.join(root, item);
    const target = path.join(tempDir, item);
    fs.cpSync(source, target, { recursive: true });
  });

  execSync(
    `powershell -NoProfile -Command "Compress-Archive -Path '${tempDir}\\*' -DestinationPath '${projectZip}' -Force"`,
    { stdio: 'inherit' }
  );
  fs.rmSync(tempDir, { recursive: true, force: true });
} else {
  execSync(`cd "${path.join(root, 'wordpress')}" && zip -r "${pluginZip}" bolly-landing`, { stdio: 'inherit' });
  execSync(`cd "${root}" && zip -r "${projectZip}" README.md SUBMISSION.md docker-compose.yml package.json preview wordpress elementor-templates scripts`, { stdio: 'inherit' });
}

console.log(`Created:\n- ${pluginZip}\n- ${projectZip}`);
