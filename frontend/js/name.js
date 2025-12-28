$(document).ready(function() {
    const userStr = localStorage.getItem('user');
    
    if (userStr) {
        try {
            const user = JSON.parse(userStr);
            const userName = user.name || user.email || 'User';
            $('#userName').text(userName);
        } catch (e) {
            console.error('Error parsing user data:', e);
            $('#userName').text('User');
        }
    }
});