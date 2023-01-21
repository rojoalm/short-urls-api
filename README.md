## Short URLs API

## Acerca del proyecto

La idea fundamental de este proyecto es implementar una API que pueda acortar URLs conectando a la API pública de TinyURL.

## Datos del desarrollo

Este proyecto fue desarrollado bajo las siguientes versiones:
- PHP 8.2.0
- Laravel 9.48.0

## Funcionamiento del desarrollo

- Llamada POST /api/v1/short-urls
- Recibe un body con el formato
```html
url: string, required
```
- Devuelve un objeto JSON con la siguiente estructura
```json
{
	"url": "https://example.com/12345"
}
```
- La autorización es tipo "Bearer Token", por ejemplo: `Authorization: Bearer my-token`. Cualquier token que cumpla con el problema de los paréntesis (descrito a continuación) es un token válido, por ejemplo: `Authorization: Bearer []{}`

Ejemplos:

- `{}` - `true`
- `{}[]()` - `true`
- `{)` - `false`
- `[{]}` - `false`
- `{([])}` - `true`
- `(((((((()` - `false`
