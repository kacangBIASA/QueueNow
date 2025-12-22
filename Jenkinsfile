pipeline {
  agent any

  environment {
    IMAGE_NAME = 'kacangbiasa/queuenow'
    REGISTRY_CREDENTIALS = 'dockerhub-tubes'
  }

  stages {
    stage('Build (info)') {
      steps { bat 'echo Build QueueNow di Windows agent...' }
    }

    stage('Unit Test (non-blocking)') {
      steps {
        // Kalau test gagal (misal pull EOF), stage jadi UNSTABLE tapi pipeline lanjut
        catchError(buildResult: 'SUCCESS', stageResult: 'UNSTABLE') {
          bat 'echo Running Laravel tests using dockerized PHP...'

          // Retry pull biar lebih tahan jaringan putus-putus
          retry(3) {
            bat 'docker pull php:8.2-cli'
          }

          bat '''
            docker run --rm ^
              -v "%CD%":/app ^
              -w /app ^
              php:8.2-cli bash -lc ^
              "apt-get update && apt-get install -y git unzip libzip-dev && docker-php-ext-install zip pdo pdo_sqlite && rm -rf /var/lib/apt/lists/* && \
               curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
               composer install --no-interaction --no-progress && \
               php artisan test"
          '''
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        // kalau file kamu namanya masih "dockerfile" (huruf kecil), ganti jadi:
        // bat 'docker build -f dockerfile -t %IMAGE_NAME%:%BUILD_NUMBER% .'
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
