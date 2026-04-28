# ITIL4f Service Documentation: TripTrack

Version: 0.3.0

Effective Date: 2026-04-28

This document describes the service architecture, value streams, and ITIL4f alignment for the TripTrack trip records service.

---

## 1. Service Overview

**Service Name:** TripTrack Trip Records

**Service Description:** A web-based service providing a filterable list of publicly accessible trip records with comprehensive lookup, CRUD operations, filters, infinite scroll, and responsive design.

**Service Type:** Information Service

---

## 2. Service Ownership

| Role | Name | Responsibility |
|------|------|----------------|
| Service Owner | Karim | Product decisions, feature prioritization, stakeholder communication |
| Service Level Manager | Karim | SLA compliance, performance monitoring, service improvement |
| Developer | Karim | Implementation, technical decisions, bug fixes |

---

## 3. Value Stream

The service value stream maps the flow from stakeholder need to service delivery:

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        SERVICE VALUE STREAM                              │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  ┌──────────────┐    ┌──────────────────┐    ┌───────────────────┐  │
│  │    ENGAGE    │───▶│  DESIGN & BUILD  │───▶│  DEPLOY & OPERATE │  │
│  └──────────────┘    └──────────────────┘    └───────────────────┘  │
│         │                       │                        │              │
│         ▼                       ▼                        ▼              │
│  Route receives        Controller processes     Model queries         │
│  user request         and validates              database             │
│                                                                       │
│  ┌──────────────────┐    ┌──────────────────┐                     │
│  │ OBTAIN & ASSURE  │───▶│    CONTINUE       │                     │
│  └──────────────────┘    └──────────────────┘                     │
│         │                       │                                      │
│         ▼                       ▼                                      │
│  Response delivered     User receives         Feedback loop         │
│  to user                value                  for improvement        │
│                                                                       │
└─────────────────────────────────────────────────────────────────────────┘
```

### 3.1 Activity Mapping

| ITIL4f Activity | Implementation |
|------------------|----------------|
| **Engage** | Route matching (`routes/web.php`), Request handling |
| **Design & Build** | TripController, Form Requests, TripRecord Model |
| **Deploy & Operate** | Laravel server, SQLite database, migrations |
| **Obtain & Assure** | Inertia response, error handling middleware |
| **Continue** | Version increments, feature additions |

---

## 4. Four Dimensions

ITIL4f organizes service management into four dimensions:

### 4.1 Organizations & People

| Aspect | Details |
|--------|---------|
| Structure | Single-developer project |
| Roles | Service Owner, Service Level Manager, Developer (combined) |
| Skills | PHP, Laravel, Vue.js, SQLite |

### 4.2 Information & Technology

| Aspect | Details |
|--------|---------|
| Web Framework | Laravel 11.x |
| Frontend | Vue.js 3 + Inertia.js |
| Database | SQLite |
| Build Tool | Vite |
| PHP Version | 8.3+ |

### 4.3 Partners & Suppliers

| Partner | Role | Relationship |
|---------|------|---------------|
| Laravel | Web framework | Open-source dependency |
| SQLite | Database | Embedded dependency |
| Inertia.js | Frontend adapter | Open-source dependency |
| Vue.js | UI framework | Open-source dependency |
| Vite | Build tool | Open-source dependency |

### 4.4 Value Streams & Processes

| Value Stream | Process |
|--------------|---------|
| Trip listing | `TripController::index()` → `TripRecord::all()` |
| Trip detail | `TripController::show()` → `TripRecord::find()` |
| Trip filtering | Query parameter processing in controller |
| Trip CRUD | Form requests + model operations |

---

## 5. Service Architecture

```
                         ┌─────────────────────┐
                         │       Client        │
                         │   (Browser/Vue.js)  │
                         └──────────┬──────────┘
                                    │ HTTP Request
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                         WEB SERVER                                     │
│                         (Laravel / PHP)                                │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│     ┌─────────────┐     ┌──────────────┐     ┌─────────────────┐      │
│     │   Routes    │────▶│ Controllers  │────▶│     Models      │      │
│     │             │     │              │     │                 │      │
│     │ /trips      │     │ TripController│    │ TripRecord     │      │
│     │ /trips/{id} │     │ UserController│    │ Station        │      │
│     └─────────────┘     └──────────────┘     └─────────────────┘      │
│                                    │                    │              │
│                                    ▼                    │              │
│                         ┌──────────────────────┐      │              │
│                         │   Middleware         │◀─────┘              │
│                         │                      │                     │
│                         │  HandleInertia      │                     │
│                         │  CompressResponse  │                     │
│                         │  MeasurePerformance│                     │
│                         └──────────────────────┘                     │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
                         ┌─────────────────────┐
                         │      DATABASE        │
                         │      (SQLite)         │
                         └─────────────────────┘
```

---

## 6. Service Level Indicators (SLI)

Measurable indicators aligned with SLA performance goals:

| SLI | Measurement Method | Target |
|-----|-------------------|--------|
| Lighthouse Score | Browser Lighthouse | >= 90 |
| Accessibility | Browser Lighthouse | >= 90 |
| First Contentful Paint | Browser Lighthouse | < 2 sec |
| Time to Interactive | Browser Lighthouse | < 3 sec |
| Server Response Time | MeasurePerformance middleware | < 500ms |

---

## 7. ITIL4f Guiding Principles Alignment

| # | Principle | Application |
|---|-----------|-------------|
| 1 | **Focus on value** | MVP approach - core features only |
| 2 | **Start where you are** | Using existing Laravel/Inertia stack |
| 3 | **Progress iteratively** | Version 0.1.0 minimal release |
| 4 | **Collaborate and promote visibility** | Public documentation, clear value streams |
| 5 | **Think and work holistically** | Full-stack integration (frontend + backend + DB) |
| 6 | **Keep it simple and practical** | Minimal dependencies, local SQLite |
| 7 | **Optimize and automate** | Lazy loading, compression, efficient queries |

---

## 8. Service Practices

ITIL4f practices implemented in this service:

| Practice | Implementation |
|----------|---------------|
| Architecture management | Service classes, clear separation |
| Change management | Laravel migrations, version control |
| Incident management | try/catch error handling |
| Service request management | Controller service operations |
| Monitoring | Performance middleware logging |

---

## 9. Version History

| Date | Version | Changes |
|------|---------|---------|
| 2026-04-20 | 0.1.0 | Initial release |