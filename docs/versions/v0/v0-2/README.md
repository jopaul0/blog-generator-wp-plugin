# Versão 0.2 - Refatoração Arquitetural (MVC)

O foco desta versão foi a organização do código e escalabilidade, adotando padrões de projeto orientados a objetos.

## Objetivos
- Implementar o padrão **MVC (Model-View-Controller)**.
- Facilitar a manutenção e a adição de futuras funcionalidades.
- Melhorar a segurança e o tratamento de dados.

## Mudanças Estruturais
- **Divisão em Classes**:
    - `AI`: Classe abstrata para lógica de prompts.
    - `Gemini_API`: Gerenciamento de requisições.
    - `ViewController`: Interceptação de formulários via `admin-post.php`.
    - `Builder`: Lógica isolada para construção física do post.
- **Segurança**: Adição de verificações de `Nonces` e `capabilities` do WordPress.
- **Internacionalização (i18n)**: Preparação do plugin para múltiplos idiomas usando arquivos `.po` e `.mo`.

## Tecnologias
- PHP Orientado a Objetos.
- Hooks nativos do WordPress para manipulação de menus e ações.