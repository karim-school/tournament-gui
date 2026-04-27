# TripTrack

## Installation

This project was built in an environment with the following versions, but they are not necessarily required.

<table>
    <tr>
        <th>PHP</th>
        <td>8.3.6</td>
    </tr>
    <tr>
        <th>Composer</th>
        <td>2.9.7</td>
    </tr>
    <tr>
        <th>Node</th>
        <td>v24.15.0</td>
    </tr>
    <tr>
        <th>NPM</th>
        <td>11.12.1</td>
    </tr>
</table>

Make sure you have the above software installed on your machine. I recommend using NVM (Node Version Manager) for Node & NPM management.

Some PHP extensions not necessarily included by default are required, such as the XML extension, mbstring extension, and sqlite3 extension. Make sure to install these and any others if you encounter errors in the build process.

Upon cloning this repository:
1. Run `composer install`
2. Run `npm install`
3. Copy the `.env.example` file to `.env`
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`

To run the software, use:
- Debug: `composer dev`
- Production: `php artisan serve`

The app will be available at http://localhost:8000.

## Service Level Agreement (SLA)

The SLA declaration can be found in the <a href="SLA.md">SLA.md</a> file.

## ITIL4f Guiding Principles

This project demonstrates the 7 ITIL4f guiding principles:

| # | Principle | Application in this project |
|---|-----------|------------------------------|
| 1 | **Focus on value** | MVP approach - only essential features (list, detail, filter, CRUD) |
| 2 | **Start where you are** | Built on existing Laravel/Inertia stack rather than from scratch |
| 3 | **Progress iteratively** | Version 0.2.0 - filters, infinite scroll, create form |
| 4 | **Collaborate and promote visibility** | Public codebase with documented value streams |
| 5 | **Think and work holistically** | Full-stack integration (frontend + backend + database) |
| 6 | **Keep it simple and practical** | Minimal dependencies, local SQLite |
| 7 | **Optimize and automate** | Lazy loading, response compression, efficient queries |

## ITIL4f Documentation

For detailed service architecture and ITIL4f alignment, see <a href="ITIL.md">ITIL.md</a>.

## Version history

<table>
    <tr>
        <th>2026-04-27</th>
        <td>0.2.2</td>
    </tr>
    <tr>
        <th>2026-04-27</th>
        <td>0.2.1</td>
    </tr>
    <tr>
        <th>2026-04-27</th>
        <td>0.2.0</td>
    </tr>
    <tr>
        <th>2026-04-20</th>
        <td>0.1.0</td>
    </tr>
</table>
