require 'zendesk_api'
require 'json'

client = ZendeskAPI::Client.new do |config|
  config.url = ENV['ZD_URL']
  config.username = ENV['ZD_USER']
  config.password = ENV['ZD_PASS']
  config.retry = true
  require 'logger'
  config.logger = Logger.new(STDOUT)
end

#Get Ticket by ID
client.tickets.find(:id => 150)