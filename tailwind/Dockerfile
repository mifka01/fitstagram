FROM node:22-slim
WORKDIR /app

# Copy package.json first
COPY package*.json ./

# Install dependencies
RUN npm install

# Now copy the rest of your application files
COPY . .

# Run your application
CMD ["npm", "run", "watch"]

