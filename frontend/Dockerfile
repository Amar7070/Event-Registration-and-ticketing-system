# Stage 1: Build the React application
FROM node:20-alpine AS build

WORKDIR /app

# Copy dependency definition files
COPY package.json package-lock.json ./

# Install dependencies using npm ci for clean build
RUN npm ci

# Copy all source files
COPY . .

# Set environment variable for API URL
# Default to '/api' which works perfectly with Nginx reverse proxy
ARG VITE_API_URL=/api
ENV VITE_API_URL=$VITE_API_URL

# Build the application
RUN npm run build

# Stage 2: Serve the React application using Nginx
FROM nginx:stable-alpine

# Copy the custom Nginx configuration
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copy build output from Stage 1 to Nginx public directory
COPY --from=build /app/dist /usr/share/nginx/html

# Expose port 80
EXPOSE 80

# Start Nginx in foreground
CMD ["nginx", "-g", "daemon off;"]
