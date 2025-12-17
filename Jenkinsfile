pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                bat 'docker build -t queuenow-app:latest .'
            }
        }

        stage('Deploy') {
            steps {
                bat '''
                docker compose down
                docker compose up -d
                '''
            }
        }
    }

    post {
        success {
            echo 'CI/CD sukses 🚀'
        }
        failure {
            echo 'CI/CD gagal ❌'
        }
    }
}
