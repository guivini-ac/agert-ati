# Gerenciamento de Assets

Este projeto não inclui arquivos binários de terceiros no repositório. Para baixar o Bootstrap, Bootstrap Icons e as fontes Poppins para uso local, execute o script:

```bash
./scripts/fetch-assets.sh
```

Os arquivos serão salvos em `assets/vendor/`, que é ignorado pelo Git. Caso o script não seja executado, o tema carregará essas dependências diretamente das CDNs oficiais.
