pipeline {
    agent any

    environment {
        // -------------------------------------------------------
        // UPDATE THIS to your Docker Hub username
        // -------------------------------------------------------
        DOCKERHUB_USERNAME = "amarsahani"
        BACKEND_IMAGE      = "${DOCKERHUB_USERNAME}/smartevent-backend"
        FRONTEND_IMAGE     = "${DOCKERHUB_USERNAME}/smartevent-frontend"
        IMAGE_TAG          = "${BUILD_NUMBER}"

        // Jenkins Credential ID for Docker Hub (username + password)
        // Create this at: Manage Jenkins -> Credentials -> Global -> Add Credentials
        DOCKERHUB_CREDS    = "dockerhub-credentials"
    }

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build Images') {
            steps {
                script {
                    sh """
                        docker build -t ${BACKEND_IMAGE}:${IMAGE_TAG}  -t ${BACKEND_IMAGE}:latest  ./backend
                        docker build -t ${FRONTEND_IMAGE}:${IMAGE_TAG} -t ${FRONTEND_IMAGE}:latest ./frontend
                    """
                }
            }
        }

        stage('Push to Docker Hub') {
            steps {
                script {
                    // Login to Docker Hub using Jenkins stored credentials
                    withCredentials([usernamePassword(
                        credentialsId: "${DOCKERHUB_CREDS}",
                        usernameVariable: 'DH_USER',
                        passwordVariable: 'DH_PASS'
                    )]) {
                        sh 'echo "$DH_PASS" | docker login -u "$DH_USER" --password-stdin'
                    }

                    // Push both the build-number tag and the 'latest' tag
                    sh """
                        docker push ${BACKEND_IMAGE}:${IMAGE_TAG}
                        docker push ${BACKEND_IMAGE}:latest

                        docker push ${FRONTEND_IMAGE}:${IMAGE_TAG}
                        docker push ${FRONTEND_IMAGE}:latest
                    """

                    echo "Pushed ${BACKEND_IMAGE}:${IMAGE_TAG} and ${FRONTEND_IMAGE}:${IMAGE_TAG} to Docker Hub"
                }
            }
        }

        stage('Deploy To Docker Compose') {
            steps {
                script {
                    // Pull the freshly pushed images and bring services up
                    sh """
                        docker compose pull backend frontend
                        docker compose down --remove-orphans || true
                        docker compose up -d
                    """
                }
            }
        }

        stage('Restart Services') {
            steps {
                script {
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
                    // -T disables pseudo-TTY — required in Jenkins CI environments
                    sh 'docker compose exec -T backend php artisan migrate --force'
                }
            }
        }

        stage('Run Seeders') {
            steps {
                script {
                    sh 'docker compose exec -T backend php artisan db:seed --force'
                }
            }
        }
    }

    post {
        always {
            // Always logout from Docker Hub after pipeline completes
            sh 'docker logout || true'
        }

        success {
            echo "Pipeline completed. Images available at:"
            echo "  docker pull ${BACKEND_IMAGE}:${IMAGE_TAG}"
            echo "  docker pull ${FRONTEND_IMAGE}:${IMAGE_TAG}"
        }

        failure {
            echo 'Pipeline failed! Check the stage logs above.'
        }
    }
}
