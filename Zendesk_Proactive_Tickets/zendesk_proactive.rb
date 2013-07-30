%w{rubygems pp logger csv zendesk_api}.each{|lib| require lib}

Message = <<-END 
 Your message goes here...
  END

not_found = []

client = ZendeskAPI::Client.new do |config|
  config.url = ""
  config.username = ""
  config.password = ""

  config.retry = true

  config.logger = Logger.new(File.open("log.txt", "w+"))
end

me = client.current_user

emails = CSV.parse(File.open("accounts.csv")).flatten
emails.each do |email|
  email.strip!
  if client.users.search(:query => email).any?
    user = client.users.search(:query => email).first
    puts "#{email} found!"
    if client.tickets.create(:subject => "Your ticket subject", :comment => { :value => Message, :author_id => me.id }, :group_id => 12345678, :assignee_id => 87654321, :priority => "normal", :status => "solved", :type => "incident", :requester_id => user.id, :tags => ["proactive"])
      puts "Ticket created."
    else
      puts "Ticket creation failed for #{email}."
    end
  else
    puts "#{email} not found!"
    not_found << email
    if client.tickets.create(:requester => {:email => email, :name => email}, :subject => "Your ticket subject", :comment => { :value => Message, :author_id => me.id }, :group_id => 123456, :assignee_id => 87654321, :priority => "normal", :status => "solved", :type => "incident", :tags => ["proactive"])
      puts "Not found email user created and ticket sent."
    else
      puts "User not found, email not sent, and user not created"
    end
  end
end

if not_found.empty?
  puts "All tickets created."
else
  pp "Number of emails not found: " + not_found.length.to_s
  pp not_found
end

