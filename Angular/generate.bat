@ECHO OFF
CALL gulp copyModule --basePath=./src/app/modules --destPath=%1 --destinationModule=%2 --sourcePath=%3
