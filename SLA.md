# Service Level Agreement: TripTrack

Version: 0.2.0

Effective Date: 2026-04-27

Service Provider: Karim

## 1. Scope of Service

This service provides a filterable list of publicly accessible trip records with the aim to collect all the data in one place with easy lookup.

### 1.2 Included Services
- Comprehensible list of records.
- Detailed view of a record.
- Filters for finding records matching a certain criteria.
- Function to add, modify and delete a record.
- Responsive design.
- Infinite scroll with data refresh.
- Create form for new records.

### 1.3 Excluded Services
- Data accuracy or otherwise correctness.

## 2. Application Coverage

<table>
    <thead>
        <tr>
            <th>Component</th>
            <th>Technology</th>
            <th>Support Level</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Web Server</td>
            <td>PHP</td>
            <td>L2 / L3</td>
        </tr>
        <tr>
            <td>App Framework</td>
            <td>Laravel</td>
            <td>L3</td>
        </tr>
        <tr>
            <td>Data Layer</td>
            <td>SQLite</td>
            <td>L2 / L3</td>
        </tr>
    </tbody>
</table>

## 3. Performance Metrics


<table>
    <thead>
        <tr>
            <th>Metric</th>
            <th>Goal</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Lighthouse Score</th>
            <td>&ge; 90</td>
        </tr>
        <tr>
            <th>Accessibility</th>
            <td>&ge; 90</td>
        </tr>
        <tr>
            <th>First Contentful Paint</th>
            <td>&lt; 2 sec</td>
        </tr>
        <tr>
            <th>Time to Interactive</th>
            <td>&lt; 3 sec</td>
        </tr>
    </tbody>
</table>

## 4. Service Ownership

| Role | Name | Contact |
|------|------|---------|
| Service Owner | Karim | [Your contact] |
| Service Level Manager | Karim | [Your contact] |

## 5. Service Value Chain

This service follows the ITIL4f value chain model:

| Activity | Implementation |
|----------|---------------|
| Engage | Route matching, request handling |
| Design & Build | Controller processing, model operations |
| Deploy & Operate | Server runtime, database queries |
| Obtain & Assure | Response delivery, error handling |
| Continue | Feature iterations |

## 6. Four Dimensions Summary

| Dimension | Coverage |
|-----------|----------|
| Organizations & People | Single developer (self) |
| Information & Technology | Laravel, Vue.js, SQLite |
| Partners & Suppliers | Open-source dependencies |
| Value Streams | Request → Response flow |

---

*For detailed ITIL4f documentation, see [ITIL.md](ITIL.md).*
