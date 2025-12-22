pipeline {
  agent any

  environment {
    // Ganti sesuai Docker Hub kamu
    IMAGE_NAME = 'delafleex/queuenow'
    REGISTRY_CREDENTIALS = 'dockerhub-credentials'
  }

  stages {
    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Build (info)') {
      steps { bat 'echo "Build QueueNow di Windows agent..."' }
    }

    // OPTIONAL: Unit Test (kalau mau cepat, bisa kamu comment dulu)
    stage('Unit Test (optional)') {
      steps {
        bat '''
          echo Running Laravel tests using dockerized PHP...
          docker run --rm -v "%CD%":/app -w /app php:8.2-cli bash -lc ^
            "apt-get update && apt-get install -y git unzip libzip-dev && docker-php-ext-install zip pdo pdo_sqlite && rm -rf /var/lib/apt/lists/* && \
             curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
             composer install --no-interaction --no-progress && \
             php artisan test"
        '''
      }
    }

    stage('Build Docker Image') {
      steps {
        // kalau kamu tidak rename dockerfile -> Dockerfile, ganti menjadi: -f dockerfile
        bat 'docker build -f Dockerfile -t %IMAGE_NAME%:%BUILD_NUMBER% .'
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
          bat 'docker push %IMAGE_NAME%:%BUILD_NUMBER%'
          bat 'docker tag %IMAGE_NAME%:%BUILD_NUMBER% %IMAGE_NAME%:latest'
          bat 'docker push %IMAGE_NAME%:latest'
        }
      }
    }
  }

  post {
    always { echo 'Selesai build' }
  }
}
