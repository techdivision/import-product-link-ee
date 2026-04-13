# AGENTS.md - import-product-link-ee

## Zweck & Verantwortung

Das `import-product-link-ee` Modul bietet **EE-spezifische Product Link Import-Funktionalität**. Es ist ein **Tier 6 Modul** und erweitert `import-product-link`.

**Hauptverantwortung:**
- EE Product Link Staging Support
- EE Sequence Actions für Product Links
- Observer Pattern für EE Link-Hooks

## Architektur & Design Patterns

### Kern-Klassen
- **EeLinkObserver**: Observer für EE-Hooks

### Verwendete Patterns
- **Observer Pattern**: Für EE-Hooks

## Abhängigkeiten

### Externe Pakete
- **Keine**

### TechDivision Dependencies
- **import-product-ee** ^27.0.0 - EE Product Importer
- **import-product-link** ^26.0.0 - Product Link Importer

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-cli-simple** - Master CLI

## Wichtige Entry Points

### Observer Klassen
```php
// EE Link Observer
EeLinkObserver::handle($row): void
```

## Events & Extension Points

**Keine Events** - Tier 6 EE-Modul

## Hints für KI-Agenten

### Wichtig zu verstehen
1. **Tier 6 Modul**: Erweitert Product Link Importer mit EE-Features
2. **EE-fokussiert**: Spezialisiert auf EE Staging
3. **Observer Pattern**: Für EE-Hooks

## Bekannte Einschränkungen

- **EE-Only**: Nur für Magento EE Deployments
- **Link-EE-Only**: Nur für EE Product Links

## Zusammenfassung

`import-product-link-ee` ist ein **Tier 6 Modul**, das EE-spezifische Product Link Import-Funktionalität bietet. Es erweitert den Product Link Importer mit EE-Features.

**Für Agenten:** Verstehe dieses Modul als **EE Product Link Importer** mit Observer Pattern.
