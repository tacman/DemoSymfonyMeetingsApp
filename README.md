# Demo Meetings App

![Database Diagram](/assets/images/db.svg)

## Notes

No UI, some tests including behat.

Updated to Symfony 6.4 on May 9, 2025 by Tac.

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
