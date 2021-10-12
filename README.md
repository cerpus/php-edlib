# Client for Cerpus Edlib Common API

See the Edlib API Documentation for steps on obtaining application id and token.  
https://docs.edlib.com/docs/developers/api-documentation/application-api/authentication

## Available endpoints

### setCollaboratorContext
Set or update collaborator context.  
https://docs.edlib.com/docs/developers/api-documentation/application-api/collaborator-contexts
```
Parameters:
    CollaboratorContext $context
    string $apiApplicationId
    string $apiToken

Return:
    GuzzleHttp\Promise\PromiseInterface
```


### generateH5pFromQa
Generate H5P from QA.  
https://docs.edlib.com/docs/developers/api-documentation/application-api/generate-h5p-from-qa

```
Parameters:
    array $body
    string $apiApplicationId
    string $apiToken

Return:
    GuzzleHttp\Promise\PromiseInterface
```
