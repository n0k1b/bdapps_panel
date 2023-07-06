<div class="dlabnav">


            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                   
                <li><a class="ai-icon" href="{{url('/')}}" aria-expanded="false">
                        <i class="la la-bar-chart"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            

			
					<li><a class="ai-icon" href="{{ route('show-all-apps') }}" aria-expanded="false">
							<i class="la la-list"></i>
							<span class="nav-text">Apps</span>
						</a>
					</li>
             
                <li><a class="ai-icon" href="{{ route('reports') }}" aria-expanded="false">
                        <i class="la la-bar-chart"></i>
                        <span class="nav-text">Report</span>
                    </a>
                </li>
                
                <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="la la-shopping-cart"></i>
                    <span class="nav-text">Content </span>
                    </a>
                    <ul aria-expanded="false">
                       
                        <li><a href="{{ route('show-all-content')}}">All Content</a></li>
                       
                        <li><a href="{{ route('select_app')}}">Regular Content</a></li>

                        <li><a href="{{ route('select_app_regular_content')}}">Instant Content</a></li>
                       

                    </ul>
                </li>
                <li><a class="ai-icon" href="{{ route('logout') }}" aria-expanded="false">
                    <i class="la la-bar-chart"></i>
                    <span class="nav-text">Logout</span>
                </a>
            </li>







				</ul>
            </div>
        </div>
