# Versão 0.3 - Ecossistema e Compatibilidade

Esta versão foca na integração do plugin com as ferramentas mais populares do ecossistema WordPress (SEO e Builders).

## Objetivos
- Garantir que o conteúdo gerado seja amigável aos motores de busca.
- Suporte nativo ao Elementor para evitar layouts quebrados.
- Diagnóstico automático do ambiente do usuário.

## Novidades
- **Compatibilidade com Elementor**:
    - Detecção automática do plugin.
    - Geração de conteúdo em widgets de "Editor de Texto" e "Título" em vez de blocos soltos.
    - Ajuste de espaçamento (`widgets_spacing`) para layout profissional.
- **Integração SEO (Switch Case)**:
    - Suporte para **Yoast SEO**, **RankMath** e **All in One SEO**.
    - Tratamento especial para palavras-chave secundárias no RankMath.
- **Painel de Diagnóstico**:
    - Nova seção em "Settings" que exibe em tempo real quais plugins de SEO e Builder foram detectados pelo sistema.
- **Aprimoramento do Resumo**: Inclusão automática do campo `post_excerpt` (resumo nativo) na criação do post.

## Correções de Bugs
- Otimização do Prompt para respeitar limites de caracteres em títulos SEO e URL Slugs.