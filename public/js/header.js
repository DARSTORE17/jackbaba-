
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu functionality
        const mobileToggle = document.getElementById('mobileToggle');
        const mobileNav = document.getElementById('mobileNav');
        const mobileClose = document.getElementById('mobileClose');
        const body = document.body;

        if (mobileToggle && mobileNav) {
            mobileToggle.addEventListener('click', function() {
                mobileNav.classList.add('active');
                body.style.overflow = 'hidden';
                // Start mobile particles when menu opens
                initMobileParticles();
            });

            mobileClose.addEventListener('click', function() {
                mobileNav.classList.remove('active');
                body.style.overflow = '';
            });

            // Close menu when clicking on mobile links
            const mobileLinks = document.querySelectorAll('.mobile-nav-link');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileNav.classList.remove('active');
                    body.style.overflow = '';
                });
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    mobileNav.classList.remove('active');
                    body.style.overflow = '';
                }
            });
        }

        // Particle Network Animation for Header
        const particlesContainer = document.getElementById('particlesContainer');
        if (particlesContainer) {
            initHeaderParticles();
        }

        function initHeaderParticles() {
            const particles = [];
            const particleLines = [];
            const particleCount = 20; // Reduced for better performance
            const connectionDistance = 120;

            // Create particles
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random size between 2px and 4px
                const size = Math.random() * 2 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random position within header
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                // Random opacity
                particle.style.opacity = Math.random() * 0.4 + 0.1;
                
                particlesContainer.appendChild(particle);
                
                // Store particle data
                particles.push({
                    element: particle,
                    x: Math.random() * 100,
                    y: Math.random() * 100,
                    vx: (Math.random() - 0.5) * 0.15,
                    vy: (Math.random() - 0.5) * 0.15
                });
            }

            // Animation loop
            function animateParticles() {
                // Update particle positions
                particles.forEach(particle => {
                    particle.x += particle.vx;
                    particle.y += particle.vy;
                    
                    // Bounce off edges
                    if (particle.x <= 0 || particle.x >= 100) particle.vx *= -1;
                    if (particle.y <= 0 || particle.y >= 100) particle.vy *= -1;
                    
                    // Apply new position
                    particle.element.style.left = `${particle.x}%`;
                    particle.element.style.top = `${particle.y}%`;
                });
                
                // Remove existing lines
                particleLines.forEach(line => {
                    if (line.parentNode) {
                        line.parentNode.removeChild(line);
                    }
                });
                particleLines.length = 0;
                
                // Create connections between nearby particles
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const p1 = particles[i];
                        const p2 = particles[j];
                        
                        // Calculate distance between particles
                        const dx = (p2.x - p1.x) * window.innerWidth / 100;
                        const dy = (p2.y - p1.y) * 70 / 100; // Header height
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        
                        // Create line if particles are close enough
                        if (distance < connectionDistance) {
                            const line = document.createElement('div');
                            line.classList.add('particle-line');
                            
                            // Calculate line properties
                            const angle = Math.atan2(dy, dx) * 180 / Math.PI;
                            const length = distance;
                            
                            // Position and style the line
                            line.style.width = `${length}px`;
                            line.style.height = '1px';
                            line.style.left = `${p1.x}%`;
                            line.style.top = `${p1.y}%`;
                            line.style.transform = `rotate(${angle}deg)`;
                            line.style.opacity = 0.8 - (distance / connectionDistance);
                            
                            particlesContainer.appendChild(line);
                            particleLines.push(line);
                        }
                    }
                }
                
                requestAnimationFrame(animateParticles);
            }
            
            // Start animation
            animateParticles();
        }

        function initMobileParticles() {
            const mobileParticlesContainer = document.getElementById('mobileParticlesContainer');
            if (!mobileParticlesContainer) return;

            // Clear existing particles
            mobileParticlesContainer.innerHTML = '';

            const particles = [];
            const particleLines = [];
            const particleCount = 15;
            const connectionDistance = 150;

            // Create particles for mobile sidebar
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random size between 2px and 5px
                const size = Math.random() * 3 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random position within mobile nav
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                // Random opacity
                particle.style.opacity = Math.random() * 0.3 + 0.1;
                
                mobileParticlesContainer.appendChild(particle);
                
                // Store particle data
                particles.push({
                    element: particle,
                    x: Math.random() * 100,
                    y: Math.random() * 100,
                    vx: (Math.random() - 0.5) * 0.1,
                    vy: (Math.random() - 0.5) * 0.1
                });
            }

            // Animation loop for mobile particles
            function animateMobileParticles() {
                if (!mobileNav.classList.contains('active')) return;

                // Update particle positions
                particles.forEach(particle => {
                    particle.x += particle.vx;
                    particle.y += particle.vy;
                    
                    // Bounce off edges
                    if (particle.x <= 0 || particle.x >= 100) particle.vx *= -1;
                    if (particle.y <= 0 || particle.y >= 100) particle.vy *= -1;
                    
                    // Apply new position
                    particle.element.style.left = `${particle.x}%`;
                    particle.element.style.top = `${particle.y}%`;
                });
                
                // Remove existing lines
                particleLines.forEach(line => {
                    if (line.parentNode) {
                        line.parentNode.removeChild(line);
                    }
                });
                particleLines.length = 0;
                
                // Create connections between nearby particles
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const p1 = particles[i];
                        const p2 = particles[j];
                        
                        // Calculate distance between particles
                        const dx = (p2.x - p1.x) * window.innerWidth / 100;
                        const dy = (p2.y - p1.y) * window.innerHeight / 100;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        
                        // Create line if particles are close enough
                        if (distance < connectionDistance) {
                            const line = document.createElement('div');
                            line.classList.add('particle-line');
                            
                            // Calculate line properties
                            const angle = Math.atan2(dy, dx) * 180 / Math.PI;
                            const length = distance;
                            
                            // Position and style the line
                            line.style.width = `${length}px`;
                            line.style.height = '1px';
                            line.style.left = `${p1.x}%`;
                            line.style.top = `${p1.y}%`;
                            line.style.transform = `rotate(${angle}deg)`;
                            line.style.opacity = 0.6 - (distance / connectionDistance);
                            
                            mobileParticlesContainer.appendChild(line);
                            particleLines.push(line);
                        }
                    }
                }
                
                requestAnimationFrame(animateMobileParticles);
            }
            
            // Start mobile particles animation
            animateMobileParticles();
        }
    });