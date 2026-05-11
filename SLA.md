# Service Level Agreement: TripTrack

Version: 0.5.1

Effective Date: 2026-05-11

Service Provider: Karim

Customer: Karim

This agreement governs the use of TripTrack in its minimum viable product (MVP) stage.

## 1. Scope of Service

This service provides a filterable list of publicly accessible trip records with the aim to collect trip data in one place with easy lookup. Furthermore, a user may register as a rider and add trips of their own.

### 1.1 Included Services
- Comprehensible list of records.
- Detailed view of a record.
- Filters for finding records matching a certain criteria.
- Function to add, modify and delete a record.
- Responsive design.
- Infinite scroll with data refresh.
- Create form for new records.

### 1.2 Excluded Services
- Data accuracy or otherwise correctness. This service relies on user input.
- Administration panel.
- Bulk selection.

## 2. Escalation

In the case the customer encounters any issues with the product, the issue will be escalated within the provider's support structure as follows.

<table>
    <thead>
        <tr>
            <th>Level</th>
            <th>Role / Team</th>
            <th>Trigger</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>L1</th>
            <td>Service Desk</td>
            <td>Initial contact, basic troubleshooting.</td>
        </tr>
        <tr>
            <th>L2</th>
            <td>Technical Support</td>
            <td>L1 cannot resolve within 30 minutes.</td>
        </tr>
        <tr>
            <th>L3</th>
            <td>Development</td>
            <td>Changes to the code; bugs.</td>
        </tr>
    </tbody>
</table>

In case the issue lies with a third-party, the provider escalate the issue to that party.

## 3. Application Coverage

The application will consist of multiple components consisting of different technologies that each play a purpose in the provided product. The components have assigned support levels for reference in case of issues. The support team will be responsible for escalating the issue if necessary.

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

## 4. Service Level Targets

### 4.1 Performance Metrics

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

### 4.2 Availability

TripTrack will use a third-party provider for hosting its services and therefore be reliant on this party. TripTrack will choose a hosting service provider with a promised availability of at least **99%** monthly. In the case this service provider breaks its Service Level Agreement for availability, TripTrack will migrate to another hosting provider to maintain a high availability.

The service is deemed available if the TripTrack product is publicly accessible to users.

For the purposes of this SLA, the following will **not** constitute unavailability:
- Force majeure events.
- Cyberattacks such as denial-of-service attacks or viruses.
- Maintenance required to preserve system stability.

### 4.3 Service Times

For normal, non-critical service, the service window is between **08:00** and **14:30** **GMT+2**.

## 5. Support & Incident Management

<table>
    <thead>
        <tr>
            <th>Priority</th>
            <th>Description</th>
            <th>Response Goal</th>
            <th>Resolution Goal</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>P1 - Critical</th>
            <td>Total system outage</td>
            <td>15 minutes</td>
            <td>4 hours</td>
        </tr>
        <tr>
            <th>P2 - High</th>
            <td>Major feature (reports) broken</td>
            <td>1 hour</td>
            <td>8 hours</td>
        </tr>
        <tr>
            <th>P3 - Medium</th>
            <td>Minor bug, workaround available</td>
            <td>4 hours</td>
            <td>3 business days</td>
        </tr>
        <tr>
            <th>P4 - Low</th>
            <td>General "How-to" questions</td>
            <td>8 hours</td>
            <td>5 business days</td>
        </tr>
    </tbody>
</table>
