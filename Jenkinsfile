pipeline {
  agent any

  options {
    timestamps()
  }

  environment {
    CI_IMAGE = "queuenow-ci:${BUILD_NUMBER}"
    APP_IMAGE = "queuenow-app:${BUILD_NUMBER}"
  }

  stages {
    stage('Checkout') {
      steps {
        checkout scm
      }
    }

    stage('Build CI Image') {
      steps {
        bat 'docker build -f Dockerfile.ci -t %CI_IMAGE% .'
      }
    }

    stage('Run Tests (Laravel)') {
      steps {
        bat 'docker run --rm %CI_IMAGE%'
      }
    }

    stage('Build App Image') {
      steps {
        bat 'docker build -t %APP_IMAGE% .'
      }
    }
  }

  post {
    always {
      // optional bersih-bersih
      bat 'docker image prune -f'
    }
  }
}
