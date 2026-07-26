@extends('layouts.adminlayouts')
@section('title', 'Admin Dashboard')

 <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">

@section('content')


  

    <main class="main">
    
        <div class="topbar">
            <div class="topbar-left">
                <h1>Dashboard</h1>
                <p>Welcome back, Admin — here's what's happening on your platform</p>
            </div>
            <div class="topbar-actions">
                <div class="notif">
                    <i class="far fa-bell"></i>
                    <span class="badge">6</span>
                </div>
                <div class="admin-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=38bdf8&color=fff&size=36" alt="admin" />
                    <span>Admin</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>

        <!-- STATS -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="label">Total Users</div>
                <div class="value">{{ number_format($totalUsers) }}</div>
                <div class="change up"><i class="fas fa-arrow-up"></i></div>
            </div>
            <div class="stat-card">
                <div class="label">Active Tasks</div>
                <div class="value">143</div>
                <div class="change up"><i class="fas fa-arrow-up"></i> +8.1%</div>
            </div>
            <div class="stat-card">
                <div class="label">Active Contracts</div>
                <div class="value">89</div>
                <div class="change up"><i class="fas fa-arrow-up"></i> +5.3%</div>
            </div>
            <div class="stat-card">
                <div class="label">Total Revenue</div>
                <div class="value">$48,230</div>
                <div class="change up"><i class="fas fa-arrow-up"></i> +18.6%</div>
            </div>
            <div class="stat-card">
                <div class="label">Pending Verifications</div>
                <div class="value">{{ number_format($pendingVerifications) }}</div>
                <div class="change down"><i class="fas fa-arrow-down"></i> -3.2%</div>
            </div>
            <div class="stat-card">
                <div class="label">Avg. Rating</div>
                <div class="value">4.8</div>
                <div class="change up"><i class="fas fa-arrow-up"></i> +0.3</div>
            </div>
        </section>

        <!-- CHARTS -->
        <div class="charts-row">
            <div class="chart-box">
                <h3>Weekly Activity <span>tasks & contracts</span></h3>
                <div class="bar-chart">
                    <div class="bar-item"><div class="bar"><div class="fill blue" style="height: 65%;"></div></div><span class="label">Mon</span></div>
                    <div class="bar-item"><div class="bar"><div class="fill green" style="height: 52%;"></div></div><span class="label">Tue</span></div>
                    <div class="bar-item"><div class="bar"><div class="fill purple" style="height: 78%;"></div></div><span class="label">Wed</span></div>
                    <div class="bar-item"><div class="bar"><div class="fill orange" style="height: 58%;"></div></div><span class="label">Thu</span></div>
                    <div class="bar-item"><div class="bar"><div class="fill pink" style="height: 88%;"></div></div><span class="label">Fri</span></div>
                    <div class="bar-item"><div class="bar"><div class="fill teal" style="height: 42%;"></div></div><span class="label">Sat</span></div>
                    <div class="bar-item"><div class="bar"><div class="fill indigo" style="height: 71%;"></div></div><span class="label">Sun</span></div>
                </div>
            </div>

            <div class="chart-box">
                <h3>Payment Distribution <span>by status</span></h3>
                <div class="donut-container">
                    <div class="donut"></div>
                    <div class="donut-legend">
                        <div class="legend-item"><span class="dot blue"></span> Released 40%</div>
                        <div class="legend-item"><span class="dot green"></span> Escrow 25%</div>
                        <div class="legend-item"><span class="dot purple"></span> Pending 20%</div>
                        <div class="legend-item"><span class="dot orange"></span> Refunded 15%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLES -->
        <div class="tables-grid">
            <!-- Recent Tasks -->
            <div class="table-section">
                <div class="table-header">
                    <h3><i class="fas fa-tasks"></i> Recent Tasks</h3>
                    <span class="badge-count">12 open</span>
                </div>
                <table>
                    <thead>
                        <tr><th>Task</th><th>Budget</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="text-truncate" style="max-width:100px;">Mobile App UI Design</span></td>
                            <td class="amount">$450</td>
                            <td><span class="status-badge active"><i class="fas fa-circle"></i> Open</span></td>
                        </tr>
                        <tr>
                            <td><span class="text-truncate" style="max-width:100px;">Backend API Development</span></td>
                            <td class="amount">$800</td>
                            <td><span class="status-badge in_progress"><i class="fas fa-circle"></i> In Progress</span></td>
                        </tr>
                        <tr>
                            <td><span class="text-truncate" style="max-width:100px;">Logo Design</span></td>
                            <td class="amount">$150</td>
                            <td><span class="status-badge completed"><i class="fas fa-circle"></i> Completed</span></td>
                        </tr>
                        <tr>
                            <td><span class="text-truncate" style="max-width:100px;">Content Writing (Blog)</span></td>
                            <td class="amount">$200</td>
                            <td><span class="status-badge pending"><i class="fas fa-circle"></i> Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Recent Contracts -->
            <div class="table-section">
                <div class="table-header">
                    <h3><i class="fas fa-handshake"></i> Recent Contracts</h3>
                    <span class="badge-count">89 total</span>
                </div>
                <table>
                    <thead>
                        <tr><th>Project</th><th>Freelancer</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="text-truncate" style="max-width:90px;">E-commerce Site</span></td>
                            <td><div class="flex-row"><span class="user-avatar blue">JD</span> John D.</div></td>
                            <td><span class="status-badge active"><i class="fas fa-circle"></i> Active</span></td>
                        </tr>
                        <tr>
                            <td><span class="text-truncate" style="max-width:90px;">Mobile App</span></td>
                            <td><div class="flex-row"><span class="user-avatar purple">SM</span> Sarah M.</div></td>
                            <td><span class="status-badge completed"><i class="fas fa-circle"></i> Completed</span></td>
                        </tr>
                        <tr>
                            <td><span class="text-truncate" style="max-width:90px;">Marketing Campaign</span></td>
                            <td><div class="flex-row"><span class="user-avatar green">AK</span> Alex K.</div></td>
                            <td><span class="status-badge pending"><i class="fas fa-circle"></i> Pending</span></td>
                        </tr>
                        <tr>
                            <td><span class="text-truncate" style="max-width:90px;">Data Analysis</span></td>
                            <td><div class="flex-row"><span class="user-avatar orange">MR</span> Maria R.</div></td>
                            <td><span class="status-badge cancelled"><i class="fas fa-circle"></i> Cancelled</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Recent Payments (full width) -->
            <div class="table-section table-full">
                <div class="table-header">
                    <h3><i class="fas fa-file-invoice-dollar"></i> Recent Payments</h3>
                    <span class="badge-count">$48,230 total</span>
                </div>
                <table>
                    <thead>
                        <tr><th>Contract</th><th>Client</th><th>Freelancer</th><th>Amount</th><th>Fee</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="text-truncate" style="max-width:100px;">E-commerce Site</span></td>
                            <td><div class="flex-row"><span class="user-avatar blue">JD</span> John D.</div></td>
                            <td><div class="flex-row"><span class="user-avatar purple">SM</span> Sarah M.</div></td>
                            <td class="amount">$1,200</td>
                            <td>$120</td>
                            <td><span class="status-badge released"><i class="fas fa-circle"></i> Released</span></td>
                        </tr>
                        <tr>
                            <td><span class="text-truncate" style="max-width:100px;">Mobile App Dev</span></td>
                            <td><div class="flex-row"><span class="user-avatar pink">AC</span> Alice C.</div></td>
                            <td><div class="flex-row"><span class="user-avatar green">AK</span> Alex K.</div></td>
                            <td class="amount">$800</td>
                            <td>$80</td>
                            <td><span class="status-badge escrow"><i class="fas fa-circle"></i> Escrow</span></td>
                        </tr>
                        <tr>
                            <td><span class="text-truncate" style="max-width:100px;">Logo Design</span></td>
                            <td><div class="flex-row"><span class="user-avatar orange">MR</span> Maria R.</div></td>
                            <td><div class="flex-row"><span class="user-avatar blue">JD</span> John D.</div></td>
                            <td class="amount">$150</td>
                            <td>$15</td>
                            <td><span class="status-badge refunded"><i class="fas fa-circle"></i> Refunded</span></td>
                        </tr>
                        <tr>
                            <td><span class="text-truncate" style="max-width:100px;">Content Writing</span></td>
                            <td><div class="flex-row"><span class="user-avatar purple">SM</span> Sarah M.</div></td>
                            <td><div class="flex-row"><span class="user-avatar pink">AC</span> Alice C.</div></td>
                            <td class="amount">$200</td>
                            <td>$20</td>
                            <td><span class="status-badge pending"><i class="fas fa-circle"></i> Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Recent Verifications -->
            <div class="table-section table-full">
                <div class="table-header">
                    <h3><i class="fas fa-id-card"></i> Pending Verifications</h3>
                    <span class="badge-count">27 pending</span>
                </div>
                <table>
                    <thead>
                        <tr><th>User</th><th>Full Name</th><th>Submitted</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class="flex-row"><span class="user-avatar blue">JD</span> @johndoe</div></td>
                            <td>John Doe</td>
                            <td>2 hours ago</td>
                            <td><span class="status-badge pending"><i class="fas fa-circle"></i> Pending</span></td>
                            <td><button class="action-btn"><i class="fas fa-ellipsis-vertical"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="flex-row"><span class="user-avatar purple">SM</span> @sarahm</div></td>
                            <td>Sarah Miller</td>
                            <td>5 hours ago</td>
                            <td><span class="status-badge pending"><i class="fas fa-circle"></i> Pending</span></td>
                            <td><button class="action-btn"><i class="fas fa-ellipsis-vertical"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="flex-row"><span class="user-avatar green">AK</span> @alexk</div></td>
                            <td>Alex King</td>
                            <td>1 day ago</td>
                            <td><span class="status-badge pending"><i class="fas fa-circle"></i> Pending</span></td>
                            <td><button class="action-btn"><i class="fas fa-ellipsis-vertical"></i></button></td>
                        </tr>
                        <tr>
                            <td><div class="flex-row"><span class="user-avatar orange">MR</span> @mariar</div></td>
                            <td>Maria Rodriguez</td>
                            <td>2 days ago</td>
                            <td><span class="status-badge pending"><i class="fas fa-circle"></i> Pending</span></td>
                            <td><button class="action-btn"><i class="fas fa-ellipsis-vertical"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
</html>

@endsection

