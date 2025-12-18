pipeline {
  agent any

  environment {
    // GANTI dengan DockerHub kamu
    IMAGE_NAME = 'DOCKERHUB_USERNAME/queuenow'
    REGISTRY_CREDENTIALS = 'dockerhub-credentials'
  }

  stages {

    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Composer Install') {
      steps {
        // Pastikan Jenkins node punya composer, atau jalankan composer via container kalau perlu
        bat 'composer install --no-dev --optimize-autoloader'
      }
    }

    stage('Build Docker Image') {
      steps {
        bat "docker build -t %IMAGE_NAME%:%BUILD_NUMBER% ."
      }
    }

    stage('Push Docker Image') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: REGISTRY_CREDENTIALS,
          usernameVariable: 'USER',
          passwordVariable: 'PASS'
        )]) {
          bat 'docker login -u %USER% -p %PASS%'
          bat "docker push %IMAGE_NAME%:%BUILD_NUMBER%"
          bat "docker tag %IMAGE_NAME%:%BUILD_NUMBER% %IMAGE_NAME%:latest"
          bat "docker push %IMAGE_NAME%:latest"
        }
      }
    }

    // DEPLOY Azure (cara aman & simple)
    stage('Deploy to Azure (Pull Latest)') {
      steps {
        echo 'Azure akan pull image latest dari Docker Hub.'
        echo 'Pastikan App Service for Containers sudah diset ke IMAGE_NAME:latest.'
      }
    }
  }

  post { always { echo 'Selesai build' } }
}
