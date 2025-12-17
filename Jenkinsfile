pipeline {
    agent any

    stages {
        stage('Checkout Code') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                bat '''
                    where composer || echo Composer tidak ditemukan di PATH
                    composer install --no-interaction --prefer-dist
                '''
            }
        }

        stage('Build Docker Image') {
            steps {
                bat '''
                    docker --version
                    docker compose version
                    docker compose build
                '''
            }
        }

        stage('Deploy Application') {
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
            echo 'CI/CD QueueNow berhasil'
        }
        failure {
            echo 'Pipeline CI/CD gagal'
        }
    }
}