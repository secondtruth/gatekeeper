# Contributing Guidelines

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [GitHub][GitHub].

## The Rules

- We try to follow Semantic Versioning ([SemVer 2.0.0][SemVer]). Randomly breaking public APIs is not an option.

- Always document your code, at least with the most important information.

- Document any change in behaviour. Make sure the `README.md` and any other relevant documentation are kept up-to-date.

- Send only one pull request per new feature. If you want to do more than one thing, send multiple pull requests.

- Please check your code for typos and spelling mistakes before making a pull request.

- If you introduce a significant code change, always run the tests.

## Coding Standard

We follow the [PSR-1 coding standard][PSR-1] and the [PSR-12 coding style][PSR-12].

## Running Tests

You can run the unit tests with the following commands:

```bash
cd path/to/repo/
composer install
phpunit
```

[GitHub]: https://github.com/secondtruth/gatekeeper
[SemVer]: http://semver.org/
[PSR-1]:  http://www.php-fig.org/psr/psr-1/
[PSR-12]: http://www.php-fig.org/psr/psr-12/