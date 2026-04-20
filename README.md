# CitiBike

## Installation

This project was built with:

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

Make sure these are installed on your machine. I recommend using NVM (Node Version Manager) for Node & NPM management.

Upon cloning this repository:
1. Run `composer install`
2. Run `npm install`
3. Copy the `.env.example` file to `.env`
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`

## Service Level Agreement (SLA)

The SLA declaration can be found in the <a href="SLA.md">SLA.md</a> file.

## Version history

<table>
    <tr>
        <th>2026-04-20</th>
        <td>0.1.0</td>
    </tr>
</table>
