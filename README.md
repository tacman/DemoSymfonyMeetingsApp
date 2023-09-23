# Demo Meetings App

## Getting Started

1. Have Docker / Docker Desktop installed on local computer
2. Run `make build` to build fresh images
3. Run `make start` to create and spin up the app
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `make down` to stop the Docker containers.

Check out Makefile to see more commands.

## Tests
Run all `make test`

### Unit
Run `make test-unit`

### Behat
Run `make test-behat`
