# Utiliser nginx comme serveur web
FROM nginx:alpine

# Copier les fichiers de l'application dans nginx
COPY . /usr/share/nginx/html

# Exposer le port 80
EXPOSE 80
