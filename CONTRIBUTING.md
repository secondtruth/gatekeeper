# Contributing Guidelines

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github][1].


## The Rules

* We try to follow Semantic Versioning ([SemVer 2.0.0][2]). Randomly breaking public APIs is not an option.

* Always document your code, at least with the most important information.

* Document any change in behaviour. Make sure the `README.md` and any other relevant documentation are kept up-to-date.

* Send only one pull request per new feature. If you want to do more than one thing, send multiple pull requests.

* Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits
    while developing, please [squash them][3] before submitting.

* Please check your code for typos and spelling mistakes before committing!

* If you introduce a significant code change, always run the tests.


## Coding Standard

We follow the [PSR-1 coding standard][4] and the [PSR-2 coding style][5].


## Running tests

You can run the unit tests with the following commands:

    $ cd path/to/Gatekeeper/
    $ composer install
    $ phpunit


  [1]: https://github.com/FlameCore/Gatekeeper
  [2]: http://semver.org/
  [3]: http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages
  [4]: http://www.php-fig.org/psr/psr-1/
  [5]: http://www.php-fig.org/psr/psr-2/
