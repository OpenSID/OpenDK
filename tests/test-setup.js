const { execSync } = require('child_process');
const path = require('path');
const fs = require('fs');

class TestSetup {
  static async clearCache() {
    try {
      console.log('Clearing application cache...');

      const commands = [
        'php artisan config:clear',
        'php artisan route:clear',
        'php artisan view:clear',
        'php artisan cache:clear'
      ];

      for (const command of commands) {
        execSync(command, {
          cwd: path.join(__dirname, '..'),
          stdio: 'inherit'
        });
      }

      console.log('Cache cleared successfully');
      return true;
    } catch (error) {
      console.error('Failed to clear cache:', error.message);
      return false;
    }
  }

  static getStorageStatePath() {
    const storageDir = path.join(__dirname, '..', 'test-results', 'storage-state');
    if (!fs.existsSync(storageDir)) {
      fs.mkdirSync(storageDir, { recursive: true });
    }
    return path.join(storageDir, 'auth.json');
  }
}

module.exports = TestSetup;
