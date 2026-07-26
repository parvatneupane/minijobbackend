@extends('layouts.adminlayouts')
@section('content')

    <main class="main">
        <!-- TOP BAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h1>Account Settings</h1>
                <p>Manage your profile and preferences</p>
            </div>
            <div class="topbar-actions">
                <div class="notif">
                    <i class="far fa-bell"></i>
                    <span class="badge">3</span>
                </div>
                <div class="admin-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=38bdf8&color=fff&size=36" alt="admin" />
                    <span>Admin</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>

        <!-- ALERT -->
        <div id="successAlert" class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span id="alertMessage">Settings updated successfully!</span>
        </div>

        <!-- ===== SETTINGS GRID ===== -->
        <div class="settings-grid">

            <!-- ===== PROFILE CARD ===== -->
            <div class="settings-card full-width">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <div>
                        <h3>Profile</h3>
                        <p>Update your personal information</p>
                    </div>
                </div>

                <!-- Avatar -->
                <div class="avatar-section">
                    <img class="avatar-preview" id="avatarPreview" 
                         src="https://ui-avatars.com/api/?name=Admin&background=38bdf8&color=fff&size=72" 
                         alt="Avatar" />
                    <div class="avatar-actions">
                        <button class="btn btn-primary btn-sm" onclick="document.getElementById('avatarInput').click()">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                        <input type="file" id="avatarInput" class="hidden-input" accept="image/*" />
                        <button class="btn btn-secondary btn-sm" onclick="removeAvatar()">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>

                <form id="profileForm" onsubmit="saveSettings(event)">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" class="form-control" value="Admin User" />
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="form-control" value="admin@freelancehub.com" disabled />
                        <div class="help-text">Email cannot be changed</div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" class="form-control" value="+1 (555) 123-4567" />
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- ===== SECURITY CARD ===== -->
            <div class="settings-card">
                <div class="card-header">
                    <i class="fas fa-lock"></i>
                    <div>
                        <h3>Security</h3>
                        <p>Change your password</p>
                    </div>
                </div>

                <form id="securityForm" onsubmit="saveSettings(event)">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" class="form-control" placeholder="Enter current password" />
                    </div>

                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" class="form-control" placeholder="Enter new password" />
                        <div class="help-text">Minimum 8 characters</div>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm new password" />
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- ===== NOTIFICATIONS CARD ===== -->
            <div class="settings-card">
                <div class="card-header">
                    <i class="fas fa-bell"></i>
                    <div>
                        <h3>Notifications</h3>
                        <p>Manage your alerts</p>
                    </div>
                </div>

                <div>
                    <div class="toggle-group">
                        <div class="toggle-label">
                            <div class="title">Email Notifications</div>
                            <div class="desc">Receive updates via email</div>
                        </div>
                        <div class="toggle active" onclick="toggleSwitch(this)">
                            <div class="toggle-knob"></div>
                        </div>
                    </div>

                    <div class="toggle-group">
                        <div class="toggle-label">
                            <div class="title">New Users</div>
                            <div class="desc">When someone registers</div>
                        </div>
                        <div class="toggle active" onclick="toggleSwitch(this)">
                            <div class="toggle-knob"></div>
                        </div>
                    </div>

                    <div class="toggle-group">
                        <div class="toggle-label">
                            <div class="title">Payments</div>
                            <div class="desc">Payment transactions</div>
                        </div>
                        <div class="toggle active" onclick="toggleSwitch(this)">
                            <div class="toggle-knob"></div>
                        </div>
                    </div>

                    <div class="toggle-group">
                        <div class="toggle-label">
                            <div class="title">Verifications</div>
                            <div class="desc">Document submissions</div>
                        </div>
                        <div class="toggle" onclick="toggleSwitch(this)">
                            <div class="toggle-knob"></div>
                        </div>
                    </div>

                    <div class="form-actions" style="border-top: none; padding-top: 12px;">
                        <button class="btn btn-primary" onclick="saveSettings(event)">
                            <i class="fas fa-save"></i> Save Preferences
                        </button>
                    </div>
                </div>
            </div>

         
            <!-- ===== DANGER ZONE ===== -->
            <div class="settings-card full-width" style="border-color: #fca5a5; background: #fef2f2;">
                <div class="card-header">
                    <i class="fas fa-triangle-exclamation" style="color: #ef4444; background: #fee2e2;"></i>
                    <div>
                        <h3 style="color: #991b1b;">Danger Zone</h3>
                        <p style="color: #7f1d1d;">Permanent actions — proceed with caution</p>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button class="btn btn-outline-danger" onclick="confirmDeactivate()">
                        <i class="fas fa-pause"></i> Deactivate Account
                    </button>
                    <button class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash-can"></i> Delete Account
                    </button>
                </div>
                <div class="help-text" style="margin-top: 12px; color: #7f1d1d;">
                    <i class="fas fa-exclamation-circle"></i> 
                    Deactivating temporarily suspends your account. Deleting is permanent and irreversible.
                </div>
            </div>

        </div>

    
    </main>

   
</body>
</html>

@endsection