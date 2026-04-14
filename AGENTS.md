# AGENTS.md - import-product-link-ee

## Zweck & Verantwortung

Das `import-product-link-ee` Modul bietet **EE-spezifische Product Link Import-Funktionalität** mit Staging und Sequence-Management. Es ist ein **Tier 6 Modul** in der EE-Import-Hierarchie und erweitert das `import-product-link` Modul mit Enterprise Edition Features.

**Hauptverantwortung:**
- EE Product Link Staging Support (zukünftige Link-Updates)
- EE Sequence Actions für Audit-Trail Link-Imports
- Observer Pattern Integration mit EE Hooks
- Staging-Table Management für Product Links
- Version und Timeline Management für Relations
- Bidirektionale Link Staging Koordination

**Modul-Kategorie:** EE Extension Module  
**Komplexität:** ⭐⭐⭐ (Mittel)  
**Abhängig von:** Magento EE Enterprise Edition

## Architektur & Design Patterns

### Kern-Klassen
- **EeLinkRepository**: EE Link-spezifische Persistierung mit Staging
- **StagingLinkRepository**: Staging-Table Management
- **SequenceActionRepository**: Audit-Trail für Link-Imports
- **EeLinkProcessor**: Service Layer für EE Link-Verarbeitung
- **EeLinkObserver**: Observer für EE Lifecycle Hooks
- **LinkStagingManager**: Koordiniert Staging für Product Links

### Verwendete Patterns
- **Observer Pattern**: Integration mit Parent Link Import Hooks
- **Repository Pattern**: Abstraktion der Staging-Datenschicht
- **Service Layer Pattern**: EE-spezifische Business Logic
- **Staging Pattern**: Zeitgesteuerte Link-Updates
- **Decorator Pattern**: Erweiterung der Base Link Repositories

## Abhängigkeiten

### Externe Pakete
- **Keine direkten PHP-Pakete**

### TechDivision Dependencies
- **import-product-ee** ^27.0.0 - EE Product Importer (Base)
- **import-product-link** ^26.0.0 - Product Link Importer (Parent)
- **import-converter-ee** - EE Conversion Framework

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-cli-simple** - Master CLI für alle Importer

### Magento EE Dependencies
- **Magento_Staging** - Core Staging Framework
- **Magento_Enterprise** - EE License Check

## Wichtige Entry Points

### Repository Klassen
```php
// EE Link Repository - mit Staging-Support
EeLinkRepository::create($row): void
EeLinkRepository::findByProductIdAndStaging($productId, $stagingId): LinkStaging

// Staging Link Repository - Staging-Tabellen-Verwaltung
StagingLinkRepository::create($row, $stagingData): void
StagingLinkRepository::findByStagingId($stagingId): array

// Sequence Action Repository - Audit-Trail
SequenceActionRepository::createAction($linkId, $action): void
```

### Observer Methods
- `EeLinkObserver::handle()` - Haupteingangspunkt für EE-Integration
- `EeLinkObserver::handleLinkStaging()` - Staging-spezifische Logik
- `EeLinkObserver::createSequenceAction()` - Audit-Trail Record

## Events & Extension Points

**Erbt Parent Events** aus import-product-link, erweitert um EE-spezifische

### Observer Hooks
- `product.import.link.staging.validate.pre` - Vor Staging-Validierung
- `product.import.link.staging.process.post` - Nach Link-Staging
- `product.import.link.sequence.action.create` - Audit-Trail Record
- `product.import.link.staging.schedule.post` - Nach Scheduling

## Database Schema

### EE-Staging-Tabellen
- **catalog_product_link_staging** - Link Staging (wie catalog_product_link)
  - `product_id`, `linked_product_id`, `link_type_id`
  - `created_in`, `updated_in` - Staging Timeline
  
- **catalog_product_link_attribute_*_staging** - Attribute Staging
  - `product_link_id`, `product_link_attribute_id`, `value`
  - `created_in`, `updated_in` - Staging Timeline

### Audit-Trail Tabellen
- **sequence_product_ee** - Sequence für Link Imports
  - `sequence_id`, `link_id`, `action_type`
  - `created_at`, `import_batch_id`

## Common Use Cases

### Use Case 1: Zukünftige Link-Updates mit Staging
```php
// CSV mit Staging-Datum:
// sku,related_sku,staging_from_date

// PROD-001,PROD-002,2026-04-30 12:00:00
// Erstellt Link in catalog_product_link_staging
```

### Use Case 2: Link-Versioning mit Audit
```php
// sequence_product_ee Eintrag erstellt für Audit-Trail
```

## Performance Considerations

### Wichtige Performance-Aspekte
1. **Staging-Overhead**: Links in Staging zusätzlich zu Live
2. **Timeline Indizes**: created_in/updated_in auf staging-Tabellen
3. **Bidirektional**: Manche Links benötigen beide Richtungen
4. **Attribute-Staging**: Separate Tabellen für Qty/Position

### Optimierungen
- Batch Staging-Inserts (max 1000 Links pro Batch)
- Nutze Transaktionen für Consistency
- Cleanup alte Staging-Links nach Schedule
- Cache Product-IDs und Link-Type-IDs

## Hints für KI-Agenten

### Kritisches Verständnis
1. **Tier 6 Modul**: EE-spezifische Extension des Link Importers
2. **Staging-fokussiert**: Arbeitet mit zukünftigen Timelines
3. **Link-Type-Management**: 4 Standard-Types mit Staging
4. **Observer Pattern**: Integration in Parent Link Import
5. **Audit-Trail**: Sequencing für Compliance

### Häufige Fehler
- ❌ Staging-Tabellen ignorieren
- ❌ Attribute-Staging nicht aktualisieren
- ❌ Timeline-Indizes nicht beachten
- ❌ Sequence-Actions nicht erstellen
- ❌ Bidirektionale Links im Staging nicht berücksichtigen

### Best Practices
- ✅ Nutze Staging-Repositories statt direkter DB-Zugriffe
- ✅ Erstelle Sequence-Actions für Audit-Trails
- ✅ Nutze Transaktionen für Multi-Table Updates
- ✅ Validiere Staging-Termine VOR Persistierung
- ✅ Implementiere Cleanup für alte Staging-Links

## Known Limitations

- **EE-Only**: Funktioniert nur auf Magento EE Deployments
- **Staging-Abhängig**: Erfordert dass Magento_Staging aktiviert ist
- **Timeline-Restriktionen**: created_in muss größer als updated_in sein
- **Performance-Overhead**: 2-3x Speicherplatz für Staging-Duplikate
- **Keine Rollback**: Staging nur über Scheduler rückgängig machbar

## Related Modules

### Direct Dependencies
- **import-product-link** - Base Product Link Importer
- **import-product-ee** - EE Product Import Framework

### Related/Companion Modules
- **import-product-grouped-ee** - EE Grouped Product Importer
- **import-product-variant-ee** - EE Configurable Product Importer
- **import-product-ee** - Base EE Product Importer

## Troubleshooting

### Problem: Staging-Links werden nicht aktiv
**Lösung:** Prüfe dass Magento_Staging aktiviert ist, Scheduler läuft, Timeline korrekt

### Problem: Attribute werden nicht im Staging gespeichert
**Lösung:** Validiere dass Link-Attribute im Staging gespeichert werden

## Zusammenfassung

`import-product-link-ee` ist ein **Tier 6 EE-Modul**, das Enterprise Edition Features für Product Link Import mit Staging und Audit-Trails bietet. Es erweitert den Base Link Importer um Staging und Versioning.

**Für KI-Agenten:** Verstehe dieses Modul als:
- **EE Product Link Importer** mit Staging Support
- **Tier 6 Extension** mit Timeline-Management
- **Link-Type-fokussiert** mit Staging für Relations
- **Audit-Trail Integration** für Tracking und Compliance
