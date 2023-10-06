FROM nginx:latest

# Remove the default Nginx configuration file
RUN rm /etc/nginx/conf.d/default.conf

# Copy the custom nginx.conf file to the container's Nginx configuration directory
COPY nginx.conf /etc/nginx/nginx.conf

# Copy your HTML content to the container's web root
COPY ./html /usr/share/nginx/html

# Expose port 80
EXPOSE 80

# Start Nginx in the foreground
CMD ["nginx", "-g", "daemon off;"]
