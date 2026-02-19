# Blog Generator - Gemini AI for WordPress

> Plugin WordPress para geração automatizada de conteúdo utilizando a inteligência artificial do Google Gemini.

![Status](https://img.shields.io/badge/status-v0.3-blue)
![PHP](https://img.shields.io/badge/php-7.4%2B-blue)
![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue)

## Sobre o Projeto (v0.3)

O **Blog Generator** é uma solução profissional para WordPress que automatiza a criação de artigos completos (Título, Conteúdo, Resumo, SEO e Tags) via Google Gemini API. A versão 0.3 consolida a robustez do plugin com foco total em **compatibilidade com o ecossistema WordPress**, garantindo integração nativa com os principais plugins de SEO e construtores de página.

---

## Novidades da Versão 0.3

- **Ecossistema Builder**: Detecção inteligente entre **Gutenberg** e **Elementor**. No Elementor, o conteúdo é agrupado em widgets de "Editor de Texto" e "Título" para evitar fragmentação excessiva.
- **Integração SEO Master**: Suporte via *Switch Case* para **Yoast SEO**, **RankMath** e **All in One SEO**, incluindo tratamento de palavras-chave secundárias.
- **Painel de Diagnóstico**: Nova interface em *Settings* que exibe em tempo real o status de detecção do Builder e do plugin de SEO ativo no servidor.
- **Arquitetura Evoluída**: Refatoração completa para o padrão **MVC**, separando lógica de negócio, controle de rotas e visualização.
- **Aprimoramento de Metadados**: Inclusão automática do rascunho de resumo (`post_excerpt`).

---

## Documentação e Versões

Para detalhes técnicos e histórico de mudanças, consulte nossa pasta de documentação:

- [v0.1 - Funcionalidade Base](docs/v0.1-funcionalidade-base.md)
- [v0.2 - Refatoração MVC](docs/v0.2-refatoracao-mvc.md)
- [v0.3 - Compatibilidade e SEO](docs/v0.3-compatibilidade-e-seo.md)

---

## Tecnologias e Arquitetura

- **Backend**: PHP 7.4+ orientado a objetos.
- **IA**: Google Gemini API (Modelos 1.5 e 2.0 Pro/Flash).
- **Design Patterns**: Implementação baseada em MVC e Controller para rotas `admin-post.php`.

---

## Como Instalar e Configurar

1. Faça o download do repositório e extraia em `wp-content/plugins/blog-generator-gemini`.
2. Ative o plugin no painel administrativo do WordPress.
3. Vá em **Blog Generator > Settings**:
   * Insira sua **Gemini API Token**.
   * Escolha o modelo da IA (Flash recomendado para velocidade).
   * Configure a **Persona** e o **Tom de Voz** para alinhar a IA ao seu nicho.

---

## Autor

**João Paulo Santos**

- LinkedIn: [João Paulo Santos](https://www.linkedin.com/in/joaosantos02/)
- Email: jopaulo.as8@gmail.com
- GitHub: [@jopaul0](https://github.com/jopaul0)