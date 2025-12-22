pipeline {
  agent any

  environment {
    IMAGE_NAME = "queuenow:${BUILD_NUMBER}"
    CONTAINER_NAME = "queuenow_ci_${BUILD_NUMBER}"
  }

  stages {
    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Build Docker Image') {
      steps {
        // INI tempat masuknya perintah docker build kamu
        bat "docker build --no-cache --progress=plain -t %IMAGE_NAME% ."
      }
    }

    stage('Smoke Test (Run Container)') {
      steps {
        script {
          // stop container kalau ada sisa
          bat """
            docker rm -f %CONTAINER_NAME% 2>NUL || exit /b 0
          """

          // jalankan container
          bat """
            docker run -d --name %CONTAINER_NAME% -p 18081:80 %IMAGE_NAME%
          """

          // tunggu sebentar lalu cek container masih hidup
          bat """
            timeout /t 8 /nobreak
            docker ps --filter "name=%CONTAINER_NAME%"
          """
        }
      }
    }
  }

  post {
    always {
      bat """
        docker rm -f %CONTAINER_NAME% 2>NUL || exit /b 0
      """
    }
  }
}
