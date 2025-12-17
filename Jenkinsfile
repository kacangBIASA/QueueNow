pipeline {
    agent any

    environment {
        APP_NAME = "queuenow"
        DOCKER_IMAGE = "queuenow-app"
    }

    stages {

        stage('Checkout Code') {
            steps {
                git branch: 'main',
                    url: 'https://github.com/kacangBIASA/QueueNow.git',
                    credentialsId: 'github-token'
            }
        }

        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t $DOCKER_IMAGE .'
            }
        }

        stage('Run Container (Test)') {
            steps {
                sh '''
                docker stop $DOCKER_IMAGE || true
                docker rm $DOCKER_IMAGE || true
                docker run -d --name $DOCKER_IMAGE -p 9000:9000 $DOCKER_IMAGE
                '''
            }
        }
    }

    post {
        success {
            echo '✅ Build & Docker berhasil'
        }
        failure {
            echo '❌ Pipeline gagal'
        }
    }
}
