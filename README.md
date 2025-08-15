# AGERT - Tema Oficial

Tema WordPress responsivo para a Agência Reguladora de Serviços Públicos Delegados do Município de Timon.

## Instalação Rápida
1. Copie a pasta do tema para `wp-content/themes/agert`.
2. No painel do WordPress, ative **AGERT - Tema Oficial**.
3. Ao ativar, o tema cria automaticamente as páginas **Reuniões**, **Anexos** e **Participantes**, além do menu "Menu Principal".

Para instruções detalhadas de instalação e requisitos, consulte o [INSTALL.md](INSTALL.md).

## Recursos
- Layout baseado em Bootstrap 5.
- Custom Post Types para reuniões, anexos e participantes.
- Formulários front-end com validação e uso de `wp_nonce_field`.
- Templates PHP otimizados para desempenho e segurança.

## Desenvolvimento
O tema utiliza apenas PHP, HTML, CSS e JavaScript simples. Nenhum build step adicional é necessário.

## Configuração de Contato
Os dados exibidos em **Contato** (endereço, telefone, e-mail e mapa) são lidos das opções do WordPress:

- `agert_contact_address`
- `agert_contact_phone`
- `agert_contact_email`
- `agert_contact_map_url`

Edite essas opções em `wp-admin/options.php` ou via um plugin de gerenciamento de opções para atualizar as informações da página.

## Assets
Os arquivos do Bootstrap, Bootstrap Icons e das fontes Poppins não são versionados. Antes de desenvolver ou implantar, execute:

```bash
./scripts/fetch-assets.sh
```

Isso criará `assets/vendor/` com as dependências. Caso não execute o script, o tema fará uso das CDNs oficiais como fallback.

