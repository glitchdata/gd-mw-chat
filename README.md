# Chatbot MediaWiki Extension

A minimal MediaWiki extension that adds `Special:Chatbot` with a simple UI plus an `action=chatbot` API module. Configure it to point at your own chatbot backend or use the built-in echo stub.

## Install
1. Copy this directory into your MediaWiki `extensions` folder as `Chatbot`.
2. Add to `LocalSettings.php`:
   ```php
   wfLoadExtension( 'Chatbot' );
   ```
3. Optional configuration in `LocalSettings.php`:
   ```php
   $wgChatbotEndpointUrl = 'https://your-backend.example/v1/chat';
   $wgChatbotApiToken = 'secret-token'; // If your backend needs bearer auth
   $wgChatbotSystemPrompt = 'You are a helpful wiki assistant.';
   $wgChatbotMaxTokens = 256;
   ```

## Usage
- Visit `Special:Chatbot` to chat interactively.
- API: `api.php?action=chatbot&message=Hello` (optional `history` as JSON string).

## Backend payload contract
The extension POSTs JSON like:
```json
{
  "system": "You are a helpful wiki assistant.",
  "messages": [
    { "role": "user", "content": "Hello" }
  ],
  "max_tokens": 256
}
```
The backend should return JSON:
```json
{
  "reply": "Hello!",
  "meta": { "model": "your-model", "usage": {"prompt_tokens": 5, "completion_tokens": 7} }
}
```

## Notes
- If `wgChatbotEndpointUrl` is empty, the extension replies with a local echo stub.
- Styling and JS live in `resources/`; backend wiring in `includes/`.
- This is intentionally small; extend as needed (rate limits, moderation, history storage, etc.).
