# Support Change Log

This project follows [Semantic Versioning](CONTRIBUTING.md).

## Proposals

We do not give estimated times for completion on `Accepted` Proposals.

- [Accepted](https://github.com/cartalyst/support/labels/Accepted)
- [Rejected](https://github.com/cartalyst/support/labels/Rejected)

---

### v2.0.1 - 2017-02-23

`UPDATED`

- use various laravel contracts.

### v2.0.0 - 2017-02-23

`UPDATED`

- use `Illuminate\Contracts\Events\Dispatcher` for events.

### v1.2.0 - 2016-06-21

`ADDED`

- `NamespacedEntityInterface` A contract for namespacing entities.

### v1.1.2 - 2015-06-24

`UPDATED`

- License to 3-clause BSD.
- Some other minor tweaks.

### v1.1.1 - 2015-02-04

`UPDATED`

- Added the ability to set custom messages and custom attributes on the Validator class.

### v1.1.0 - 2015-01-23

`ADDED`

- `Collection` A Collection class, similar to the Laravel Collection but more simpler.
- `Mailer` A Mailer class that implements the `Illuminate\Mail\Mailer` with lots of helper methods.
- `Validator` A Validation class that allows you to define different rules for different scenarios throughout your application.
- `ContainerTrait` Common methods and properties for accessing the Laravel IoC.
- `MailerTrait` Common methods and properties for sending emails.
- `ValidatorTrait` Common methods and properties for doing validation.

### v1.0.1 - 2015-06-24

`UPDATED`

- License to 3-clause BSD.
- Some other minor tweaks.

### v1.0.0 - 2014-08-07

`INIT`

- `EventTrait` Common methods and properties for dispatching events.
- `RepositoryTrait` Common methods and properties for use across repositories.
