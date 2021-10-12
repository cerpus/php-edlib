# Client for Cerpus Edlib Common API


## Authenticaton

See the [Edlib API Documentation](https://docs.edlib.com/docs/developers/api-documentation/application-api/authentication)
for steps on obtaining application id and token.
Set the credentials either by using the `setCredentials` method, or by creating an instance of `EdlibApiClient` and providing them in the config.
Which headers to set are described in the documentation in the [Make authenticated requests](https://docs.edlib.com/docs/developers/api-documentation/application-api/authentication#make-authenticated-requests) section.

## Available endpoints

### setCollaboratorContext
Set or update collaborator context.  
https://docs.edlib.com/docs/developers/api-documentation/application-api/collaborator-contexts
```
Parameters:
    CollaboratorContext $context

Return:
    GuzzleHttp\Promise\PromiseInterface
```


### generateH5pFromQa
Generate H5P from QA.  
https://docs.edlib.com/docs/developers/api-documentation/application-api/generate-h5p-from-qa

```
Parameters:
    array $body

Return:
    GuzzleHttp\Promise\PromiseInterface
```
