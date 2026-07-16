const { exec } = require('child_process');
const fs = require('fs');

console.log('Setting up WordPress...');

// Wait for WordPress to be ready
setTimeout(() => {
    console.log('Installing WordPress...');
    
    // Install WordPress
    exec('docker exec frontend_assignment_wordpress_1 wp core install --url="http://localhost:8080" --title="Bolly Landing" --admin_user="admin" --admin_password="admin123" --admin_email="admin@example.com" --skip-email', (error, stdout, stderr) => {
        if (error) {
            console.log('WordPress might already be installed or container not ready');
        } else {
            console.log('WordPress installed successfully!');
        }
        
        // Activate plugin
        exec('docker exec frontend_assignment_wordpress_1 wp plugin activate bolly-landing', (error, stdout, stderr) => {
            if (error) {
                console.log('Plugin activation might have issues:', error);
            } else {
                console.log('✅ WordPress setup complete!');
                console.log('🔗 Site URL: http://localhost:8080');
                console.log('🔗 Admin URL: http://localhost:8080/wp-admin');
                console.log('👤 Login: admin');
                console.log('🔑 Password: admin123');
            }
        });
    });
}, 10000);