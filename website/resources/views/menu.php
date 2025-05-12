    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Arsip Surat</title>
    <link rel="stylesheet" href="../css/appm.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </head>
    <body>
    <div class="sidebar">
        <div class="sidebar-header">
        <h3></h3>
        <img src="../img/logo.svg" alt="Logo Perusahaan" class="logo-perusahaan" />
        <p>Admin</p>
        </div>
        <ul class="menu">
        <li class="active">BERANDA</li>
        <li>TEMPLATE SURAT</li>
        <li>DATA SURAT</li>
        <li>PEGAWAI</li>
        <li>PELAPORAN</li>
        </ul>
        <div class="logout">
        <a href="#">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="navbar">
            <span class="menu-toggle material-icons">menu</span>
            <div class="user-dropdown"*>
                <h3>PROFILE<h3>
            </div>
        </div>

        <div class="header">
        <h2>Sistem Informasi Arsip Surat Masuk dan Surat Keluar PT. YTTA</h2>
        </div>

        <div class="cards">
        <div class="card masuk">
            <h3>0</h3>
            <p>Surat Masuk</p>
            <button>Selengkapnya</button>
        </div>
        <div class="card keluar">
            <h3>0</h3>
            <p>Surat Keluar</p>
            <button>Selengkapnya</button>
        </div>
        </div>

        <div class="calendar">
        <h4>JULI 2021</h4>
        <table>
            <thead>
            <tr>
                <th>MINGGU</th><th>SENIN</th><th>SELASA</th><th>RABU</th><th>KAMIS</th><th>JUMAT</th><th>SABTU</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td class="active">9</td><td>10</td></tr>
            <tr><td>11</td><td>12</td><td>13</td><td>14</td><td>15</td><td>16</td><td>17</td></tr>
            <tr><td>18</td><td>19</td><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td></tr>
            <tr><td>25</td><td>26</td><td>27</td><td>28</td><td>29</td><td>30</td><td>31</td></tr>
            </tbody>
        </table>
        </div>
    </div>
    </body>
    </html>
