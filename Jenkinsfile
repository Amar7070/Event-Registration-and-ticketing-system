pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build Images') {
            steps {
                script {
                    sh 'docker compose build'
                }
            }
        }

        stage('Deploy To Docker Compose') {
            steps {
                script {
                    // Start or update all services in detached mode
                    sh 'docker compose up -d'
                }
            }
        }

        stage('Restart Services') {
            steps {
                script {
                    // Restart backend and frontend services to ensure fresh configurations
                    sh 'docker compose restart backend frontend'
                }
            }
        }

        stage('Wait For Backend') {
            steps {
                script {
                    echo 'Waiting for backend container to stabilize...'
                    sh 'sleep 10'
                }
            }
        }

        stage('Run Migrations') {
            steps {
                script {
                    // Execute Laravel migrations inside the running backend container
                    // Note: -T is used to disable pseudo-TTY allocation (essential for CI environments like Jenkins)
                    sh 'docker compose exec -T backend php artisan migrate --force'
                }
            }
        }

        stage('Run Seeders') {
            steps {
                script {
                    // Execute database seeders inside the running backend container
                    sh 'docker compose exec -T backend php artisan db:seed --force'
                }
            }
        }
    }

    post {

        success {
            echo 'Docker Compose deployment completed successfully!'
        }

        failure {
            echo 'Pipeline failed!'
        }
    }
}
