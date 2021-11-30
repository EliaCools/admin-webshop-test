<?php
namespace Deployer;

require 'recipe/symfony4.php';

// Project name
set('application', 'deployer-deployed');

// don't want to install composer on my local machine (just testing deployer locally now) @todo change this when on remote server

set('composer_options',"{{ composer_action }} --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-suggest");
// Project repository
set('repository', 'https://github.com/EliaCools/deployer-test.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', ['.env.local']);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);

set('env', [
    'APP_ENV' => 'prod',
]);

// Hosts

host('deployertestdeployed.local')
    ->set('deploy_path', '/var/www/{{application}}');
    
// Tasks
task('test', function() {
    writeln('{{application}}');
});

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

