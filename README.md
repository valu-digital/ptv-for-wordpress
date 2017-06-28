# PTV for WordPress

## Installation

- Clone or download this repository
- Run `composer install` in the plugin folder. Composer installs two dependencies: PTV Client for WordPress and Carbon Fields 2 (still in beta).
- Install and enable Polylang plugin
- Create `fi`, `sv`, and `en` languages in the Polylang settings.
- Define following constants in the wp-config.php. API urls in the example are for the PTV training environment.

```
define( 'PTV_API_USER', 'Email address of PTV API user' );
define( 'PTV_API_SECRET', 'Password for the PTV API user' );
define( 'PTV_API_URL', 'https://api.palvelutietovaranto.trn.suomi.fi' ); 
define( 'PTV_API_TOKEN_URL', 'https://sts.palvelutietovaranto.trn.suomi.fi/connect/token' );
define( 'PTV_FOR_WORDPRESS_REST_TOKEN', 'Token for rest api calls' );
```

- Activate PTV for WordPress plugin
- Define primary language and organization ID in the plugin settings page.

## Usage

Plugin mode is set to `out` by default. Mode can be changed by adding `define( 'PTV_FOR_WORDPRESS_MODE', 'in' );` to the wp-config.php.

**Always test plugin and its updates in the staging environment before adding it to the production environment.**

### Importing services and service channels from PTV OUT API.

Plugin uses background processing library to handle the import process. Import can be triggered by following curl command.

Replace token with the one defined in your the wp-config.php file.

```curl -v -X POST http://localhost/wp-json/ptv/v1/all -d token=PTV_FOR_WORDPRESS_REST_TOKEN```

You can also import services, service channels or organizations independently:

```
curl -v -X POST http://localhost/wp-json/ptv/v1/services -d token=PTV_FOR_WORDPRESS_REST_TOKEN
curl -v -X POST http://localhost/wp-json/ptv/v1/service-channels -d token=PTV_FOR_WORDPRESS_REST_TOKEN
curl -v -X POST http://localhost/wp-json/ptv/v1/organizations -d token=PTV_FOR_WORDPRESS_REST_TOKEN
```

### Creating and updating new services and service channels using PTV IN API.

Change plugin mode in the wp-config.php `define( 'PTV_FOR_WORDPRESS_MODE', 'in' );`

Using "in mode" requires importing of some taxonomy terms. These terms can be found from the [resources](resources) folder as a JSON format.

Taxonomies needs to be imported manually for now. WordPress importer compatible WXR file will be available later.