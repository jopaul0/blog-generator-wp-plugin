# Versão 0.1 - Funcionalidade Base

Esta é a versão inicial do plugin, focada em validar a integração entre o WordPress e a API do Google Gemini.

## Objetivos
- Estabelecer conexão com o Google AI Studio.
- Permitir a geração de posts a partir de um tema central.
- Automação básica de inserção de conteúdo no banco de dados do WP.

## Funcionalidades
- **Integração com Gemini API**: Envio de prompts básicos para geração de texto.
- **Formulário Simples**: Interface administrativa básica para entrada de tema e quantidade de palavras.
- **Criação de Rascunhos**: O conteúdo gerado é salvo automaticamente como `draft` (rascunho) para revisão.
- **Configurações Básicas**: Campo para salvar a API Key e escolha de idioma.

## Arquitetura
- Código procedural contido majoritariamente no arquivo principal.
- Chamadas diretas à API usando `wp_remote_post`.