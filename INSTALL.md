# Guia de Instalação

Este documento descreve o passo a passo para instalar e ativar o tema **AGERT - Tema Oficial** em uma instância WordPress.

## Requisitos
- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- Acesso ao diretório `wp-content/themes/` do seu site

## Instalação
1. Baixe ou clone este repositório dentro de `wp-content/themes/`:
   ```bash
   cd wp-content/themes/
   git clone https://github.com/agert/agert-php.git agert
   ```
2. No painel administrativo do WordPress, acesse **Aparência > Temas** e ative **AGERT - Tema Oficial**.
3. Após a ativação, o tema criará automaticamente as páginas **Reuniões**, **Anexos** e **Participantes**, além do menu "Menu Principal".

## Pós-instalação
- Configure os links permanentes em **Configurações > Links Permanentes** para garantir o funcionamento correto das rotas.
- Personalize o tema conforme necessário em **Aparência > Personalizar**.

## Atualização
Para atualizar o tema, dentro do diretório do tema execute:
```bash
git pull origin main
```

